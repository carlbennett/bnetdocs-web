#!/usr/bin/env python3

# @project bnetdocs-web <https://github.com/BNETDocs/bnetdocs-web/>

import argparse
import requests
import socket
import json
from concurrent.futures import ThreadPoolExecutor, as_completed

CONFIG_FILE = "../etc/config.phoenix.json"

def load_config(config_file):
    with open(config_file) as f:
        return json.load(f)

def fetch_servers(api_url):
    response = requests.get(api_url)
    response.raise_for_status()
    servers = response.json().get("servers", [])
    return servers

def check_server_status(server, updates, timeout):
    ip = server["address"]
    port = server["port"]
    status_bitmask = server["status_bitmask"]
    id = server["id"]

    if status_bitmask & 2:  # Disabled
        print(f"Skipping {ip}:{port}")
        return

    try:
        with socket.create_connection((ip, port), timeout=timeout):
            new_status = 1  # online
            if not (status_bitmask & 1):  # was offline
                updates[id] = new_status
                print(f"{ip}:{port} status updated to online")
    except socket.timeout:
        print(f"Timeout occurred while checking {ip}:{port}")
        new_status = 0  # offline
        if status_bitmask & 1:  # was online
            updates[id] = new_status
            print(f"{ip}:{port} status updated to offline due to timeout")
    except socket.error:
        print(f"Error occurred while checking {ip}:{port}")
        new_status = 0  # offline
        if status_bitmask & 1:  # was online
            updates[id] = new_status
            print(f"{ip}:{port} status updated to offline due to error")

def submit_updates(job_url, job_token, updates):
    for server_id, status in updates.items():
        data = {'id': server_id, 'job_token': job_token, 'status': status}
        response = requests.post(job_url, data=data)
        response.raise_for_status()
        print(f"Submitted update for server {server_id} with status {status}")

def main():
    parser = argparse.ArgumentParser(description="Server status checker")
    parser.add_argument("--collection_url", required=True, help="URL to fetch servers")
    parser.add_argument("--submission_url", required=True, help="URL to submit updates")
    parser.add_argument("--threads", type=int, default=10, help="Number of threads for checking servers")
    parser.add_argument("--timeout", type=int, default=5, help="Timeout for each server connection in seconds")
    args = parser.parse_args()

    config = load_config(CONFIG_FILE)
    job_token = config["bnetdocs"]["server_update_job_token"]

    servers = fetch_servers(args.collection_url)
    updates = {}

    with ThreadPoolExecutor(max_workers=args.threads) as executor:
        futures = {executor.submit(check_server_status, server, updates, args.timeout): server for server in servers}
        for future in as_completed(futures):
            future.result()  # Collect results

    if updates:
        submit_updates(args.submission_url, job_token, updates)

if __name__ == "__main__":
    main()

