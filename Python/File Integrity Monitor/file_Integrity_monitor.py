import hashlib
import time
import os
from datetime import datetime

def calculate_sha256(file_path):
    sha256_hash = hashlib.sha256()
    try:
        with open(file_path, "rb") as f:
            for byte_block in iter(lambda: f.read(4096), b""):
                sha256_hash.update(byte_block)
        return sha256_hash.hexdigest()
    except FileNotFoundError:
        return None
    except PermissionError:
        print(f"[!] Error: Insufficient permissions to read file: {file_path}")
        return None

def start_fim(target_file, check_interval=3):
    if not os.path.exists(target_file):
        print(f"[-] Error: Target file '{target_file}' does not exist!")
        return

    print("=" * 60)
    print("[*] Starting File Integrity Monitoring (FIM) Tool")
    print(f"[*] Monitored File: {os.path.abspath(target_file)}")
    print(f"[*] Check Interval: Every {check_interval} seconds")
    print("=" * 60)

    baseline_hash = calculate_sha256(target_file)
    if not baseline_hash:
        return

    print("[+] Baseline SHA-256 Hash created:")
    print(f"    {baseline_hash}\n")
    print("[*] Monitoring continuously... (Press Ctrl+C to stop)\n")

    try:
        while True:
            time.sleep(check_interval)
            
            current_hash = calculate_sha256(target_file)
            current_time = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

            if current_hash is None:
                print(f"\n[CRITICAL ALERT] [{current_time}] Target file was deleted or moved!")
                break

            if current_hash != baseline_hash:
                print("!" * 60)
                print(f"[CRITICAL ALERT] [{current_time}] Unauthorized modification detected!")
                print(f"  - Affected File : {target_file}")
                print(f"  - Original Hash : {baseline_hash}")
                print(f"  - Current Hash  : {current_hash}")
                print("!" * 60 + "\n")
                
                baseline_hash = current_hash
            else:
                print(f"[OK] [{current_time}] File is intact. No changes detected.")

    except KeyboardInterrupt:
        print("\n[*] Monitoring tool stopped manually by user.")

if __name__ == "__main__":
    file_to_monitor = "sensitive_config.txt"
    
    if not os.path.exists(file_to_monitor):
        with open(file_to_monitor, "w", encoding="utf-8") as f:
            f.write("Server_IP=192.168.1.10\nAdmin_User=root\n")
        print(f"[*] Created sample test file: {file_to_monitor}\n")

    start_fim(file_to_monitor, check_interval=3)
