
# InnoByte — Class Assignment Website

Pages:
- `index.html` — Home
- `about.html` — About
- `products.html` — Products/Services
- `news.html` — News
- `contact.php` — Contacts (PHP reads from `data/contacts.txt`)

Contacts file format:
```
Name|Role|Email|Phone
```
Lines starting with `#` are ignored.

Deploy (InfinityFree):
1. Create a free subdomain (e.g., `innobyte.epizy.com` or `innobyte.rf.gd` depending on what's offered).
2. Open File Manager and upload the contents of `innobyte-site` into `htdocs/`.
3. Ensure `data/contacts.txt` is present. Visit `/contact.php` to verify.
