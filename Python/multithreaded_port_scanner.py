import socket
import concurrent.futures
import time

def scan_port(target, port):
    try:
        with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as sock:
            sock.settimeout(1)
            if sock.connect_ex((target, port)) == 0:
                print(f"[OPEN] Port {port} is open")
                return port
    except socket.error:
        pass
    return None

def main():
    target = input("Enter Target IP: ").strip()

    try:
        socket.gethostbyname(target)
    except socket.gaierror:
        print("Invalid IP address or hostname.")
        return

    start_time = time.time()
    print(f"\nScanning Target: {target}")
    print("-" * 40)

    open_ports = []
    with concurrent.futures.ThreadPoolExecutor(max_workers=50) as executor:
        futures = [executor.submit(scan_port, target, p) for p in range(1, 1025)]
        for f in concurrent.futures.as_completed(futures):
            if f.result():
                open_ports.append(f.result())

    print("-" * 40)
    if open_ports:
        print(f"Open ports: {sorted(open_ports)}")
    else:
        print("No open ports found.")
    print("Scanning completed!")
    print(f"Time taken: {time.time() - start_time:.2f} seconds")

if __name__ == "__main__":
    main()
