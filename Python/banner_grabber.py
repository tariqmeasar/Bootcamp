
import socket

def banner_grabbing(target, port):

    try:
        # إنشاء اتصال TCP
        sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

        # تحديد المهلة الزمنية
        sock.settimeout(3)

        # الاتصال بالهدف والمنفذ
        sock.connect((target, port))

        # إرسال طلب HTTP للمنافذ الخاصة بالويب
        if port in [80, 8080, 8000, 8888]:
            request = f"HEAD / HTTP/1.1\r\nHost: {target}\r\nConnection: close\r\n\r\n"
            sock.send(request.encode())

        # استقبال البانر
        banner = sock.recv(1024)

        # تحويل البيانات إلى نص
        banner = banner.decode('utf-8', errors='ignore')

        # استخراج السطر الأول
        if banner:
            result = banner.splitlines()[0]
            print(f"[+] Port {port} : {result}")
        else:
            print(f"[-] Port {port} : No Banner Found")

        # إغلاق الاتصال
        sock.close()

    except socket.timeout:
        print(f"[!] Port {port} : Connection Timeout")

    except ConnectionRefusedError:
        print(f"[-] Port {port} : Connection Refused")

    except socket.error as e:
        print(f"[!] Port {port} : Error - {e}")


# تحديد الهدف
target = input("Enter Target IP or Domain: ")

# المنافذ المراد فحصها
ports = [21, 22, 25, 80, 110, 143, 443, 8080]

print("\nStarting Banner Grabbing...\n")

# فحص المنافذ
for port in ports:
    banner_grabbing(target, port)

print("\nScan Completed.")
