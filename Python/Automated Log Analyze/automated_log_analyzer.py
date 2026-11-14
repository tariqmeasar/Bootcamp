import re
from collections import Counter

# أنماط اكتشاف الهجمات الأمنية
patterns = {
    "SQL Injection": re.compile(
        r"UNION\s+SELECT|SELECT\s+.*FROM|OR\s+['\"]?\d+['\"]?\s*=\s*['\"]?\d+|DROP\s+TABLE",
        re.IGNORECASE
    ),

    "Path Traversal": re.compile(
        r"\.\./|%2e%2e%2f|/etc/passwd",
        re.IGNORECASE
    )
}

# إحصائيات الهجمات
attack_count = Counter()
total_attacks = 0

# اسم ملف السجلات
log_file = "access.log"

print("=" * 55)
print("       AUTOMATED SECURITY LOG ANALYZER")
print("=" * 55)

try:
    # قراءة ملف السجلات سطرا بسطر
    with open(log_file, "r", encoding="utf-8") as file:

        for line_number, line in enumerate(file, start=1):

            # استخراج عنوان IP
            ip_match = re.search(
                r"^\s*(\d{1,3}(?:\.\d{1,3}){3})",
                line
            )

            ip_address = ip_match.group(1) if ip_match else "Unknown"

            # فحص السجل باستخدام أنماط Regex
            for attack_type, pattern in patterns.items():

                if pattern.search(line):

                    total_attacks += 1
                    attack_count[attack_type] += 1

                    print("\n[!] SECURITY ALERT")
                    print(f"Attack Type : {attack_type}")
                    print(f"IP Address  : {ip_address}")
                    print(f"Line Number : {line_number}")
                    print(f"Log Entry   : {line.strip()}")

                    break

    # التقرير النهائي
    print("\n" + "=" * 55)
    print("              SCAN SUMMARY")
    print("=" * 55)

    print(f"Total Attacks Detected: {total_attacks}")

    for attack_type, count in attack_count.items():
        print(f"{attack_type}: {count}")

    print("=" * 55)
    print("Log Analysis Completed Successfully.")

except FileNotFoundError:
    print(f"[ERROR] File '{log_file}' was not found.")

except PermissionError:
    print("[ERROR] Permission denied while reading the log file.")

except Exception as error:
    print(f"[ERROR] {error}")
