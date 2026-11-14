import secrets
import string
import hashlib


def generate_secure_password(length=12):
    if length < 8:
        raise ValueError("Password length must be at least 8 characters.")

    uppercase = string.ascii_uppercase
    lowercase = string.ascii_lowercase
    digits = string.digits
    punctuation = string.punctuation
    all_characters = string.ascii_letters + string.digits + string.punctuation

    password_chars = [
        secrets.choice(uppercase),
        secrets.choice(lowercase),
        secrets.choice(digits),
        secrets.choice(punctuation),
    ]

    remaining_length = length - 4
    for _ in range(remaining_length):
        password_chars.append(secrets.choice(all_characters))

    secrets.SystemRandom().shuffle(password_chars)

    return "".join(password_chars)


def calculate_sha256(text):
    encoded_text = text.encode("utf-8")
    hash_object = hashlib.sha256(encoded_text)
    return hash_object.hexdigest()


def main():
    print("=" * 55)
    print("   Password Generator & SHA-256 Hasher Tool   ")
    print("=" * 55)

    while True:
        print("\nSelect an option:")
        print("1. Generate a secure password and calculate its SHA-256 hash")
        print("2. Calculate SHA-256 hash for custom text")
        print("3. Exit")

        choice = input("\nEnter your choice (1/2/3): ").strip()

        if choice == "1":
            try:
                length = int(input("Enter desired password length (default 12): ") or 12)
                password = generate_secure_password(length)
                hashed_password = calculate_sha256(password)

                print("\n--- Results ---")
                print(f"Generated Password : {password}")
                print(f"SHA-256 Hash (Hex) : {hashed_password}")
            except ValueError as e:
                print(f"Input Error: {e}")

        elif choice == "2":
            custom_text = input("Enter text to hash: ")
            if custom_text:
                hashed_text = calculate_sha256(custom_text)
                print("\n--- Results ---")
                print(f"Original Text      : {custom_text}")
                print(f"SHA-256 Hash (Hex) : {hashed_text}")
            else:
                print("Please enter non-empty text.")

        elif choice == "3":
            print("Exiting tool. Goodbye!")
            break

        else:
            print("Invalid choice. Please select 1, 2, or 3.")


if __name__ == "__main__":
    main()
