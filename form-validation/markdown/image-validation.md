# Profile Image Upload Validation — Revision Notes (PHP)

## Poora Code (Reference)

```php
if (empty($profile) || !isset($profile['error']) || $profile['error'] === UPLOAD_ERR_NO_FILE) {
    $profileError = 'Please select a profile image.';
} elseif ($profile['error'] !== UPLOAD_ERR_OK) {
    $profileError = 'There was an error uploading the profile image.';
} elseif ($profile['size'] > 1 * 1024 * 1024) {
    $profileError = 'Maximum file size should be 1MB.';
} elseif ($profile['size'] === 0) {
    $profileError = 'The uploaded file is empty.';
} else {
    $allowedMimeToExt = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($profile['tmp_name']);

    if (!isset($allowedMimeToExt[$mimeType])) {
        $profileError = 'Invalid file format. Only JPG, PNG, and WEBP are allowed.';
    } elseif (@getimagesize($profile['tmp_name']) === false) {
        $profileError = 'The uploaded file is not a valid image.';
    } else {
        $extension = $allowedMimeToExt[$mimeType];
    }
}
```

---

## Step-by-Step Breakdown

### Step 1 — File bheji bhi gayi hai ya nahi?
```php
empty($profile) || !isset($profile['error']) || $profile['error'] === UPLOAD_ERR_NO_FILE
```
| Condition | Kya check karta hai |
|---|---|
| `empty($profile)` | `$profile` array set hi nahi hua |
| `!isset($profile['error'])` | Defensive check — `error` key missing hai (malformed/tampered data) |
| `$profile['error'] === UPLOAD_ERR_NO_FILE` | User ne file select hi nahi ki |

**Yaad rakhna:** Hamesha `error` key pehle check karo, kabhi directly `size`/`tmp_name` access mat karo — warna "undefined key" warning aa sakti hai.

---

### Step 2 — Upload technically successful hua?
```php
$profile['error'] !== UPLOAD_ERR_OK
```
- `UPLOAD_ERR_OK` (value `0`) = **sirf yehi** success case hai.
- Baaki koi bhi value ka matlab kuch na kuch galat hua (size limit cross, upload adhoora, disk issue, etc.)

**PHP Upload Error Constants (yaad karo):**

| Constant | Value | Matlab |
|---|---|---|
| `UPLOAD_ERR_OK` | 0 | Success |
| `UPLOAD_ERR_INI_SIZE` | 1 | php.ini limit cross |
| `UPLOAD_ERR_FORM_SIZE` | 2 | HTML form limit cross |
| `UPLOAD_ERR_PARTIAL` | 3 | Adhuri upload |
| `UPLOAD_ERR_NO_FILE` | 4 | File select nahi ki |
| `UPLOAD_ERR_NO_TMP_DIR` | 6 | Temp folder missing |
| `UPLOAD_ERR_CANT_WRITE` | 7 | Disk write fail |

---

### Step 3 — Size sahi range mein hai?
```php
$profile['size'] > 1 * 1024 * 1024   // Max limit
$profile['size'] === 0                // Empty file
```
- `1 * 1024 * 1024` = 1,048,576 bytes = **1MB** (max allowed)
- `=== 0` check zaroori kyunki empty file `getimagesize()` ko silently fail kara sakti hai (edge case bug)

---

### Step 4 — Real file content check (SABSE IMPORTANT PART) 🔒
```php
$allowedMimeToExt = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
];

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($profile['tmp_name']);
```

**`finfo` kya karta hai:**
- File ke **actual bytes/signature** padhta hai (jaise JPEG hamesha `FF D8 FF` se start hoti hai)
- **Filename ya extension bilkul ignore karta hai** — sirf real content dekhta hai
- Isiliye `virus.exe` ko `photo.jpg` rename karne se bhi ye pakad lega ki ye image nahi hai

**Concept yaad rakhna:**
> Extension = filename pe based (user-controlled, **unreliable**)
> MIME type = actual content pe based (server-verified, **reliable**)

```php
if (!isset($allowedMimeToExt[$mimeType])) {
    $profileError = 'Invalid file format...';
}
```
- Jo real `$mimeType` mila, use lookup table mein dhoondo
- Table mein nahi mila → reject

---

### Step 5 — Kya ye genuinely valid image hai?
```php
elseif (@getimagesize($profile['tmp_name']) === false) {
    $profileError = 'The uploaded file is not a valid image.';
}
```
- `getimagesize()` file ko **actually parse** karta hai (width/height nikaalne ki koshish)
- Corrupt ya fake file ho toh `false` return karta hai
- `@` symbol = PHP warnings ko silently suppress karta hai
- Ye **double-check** hai — MIME type pass hone ke baad bhi ensure karta hai file genuinely usable hai

---

### Step 6 — Extension finalize karo (MIME se, filename se NAHI)
```php
else {
    $extension = $allowedMimeToExt[$mimeType];
}
```
- Extension **real MIME type se derive** hota hai, user ke diye naam se nahi
- Isse guarantee: agar content PNG hai, extension hamesha `.png` hoga — kabhi mismatch nahi

---

## 🎯 Poora Flow (Ek Nazar Mein)

```
File select hui?
    ↓ Haan
Upload error code OK tha?
    ↓ Haan
Size 0 se zyada aur 1MB se kam?
    ↓ Haan
Real MIME type allowed list mein? (finfo check)
    ↓ Haan
getimagesize() se valid image prove hui?
    ↓ Haan
Extension = MIME type se derive karo
    ↓
✅ File save karne ke liye ready
```

---

## 🔑 Sabse Important Takeaway

> **Filename kabhi trust mat karo** (user control karta hai) — **hamesha real file content check karo** (`finfo` se MIME type, `getimagesize()` se validity). Extension bhi content se hi derive karo, filename se nahi.

## Quick Checklist (Revision ke liye)

- [ ] `isset($profile['error'])` — defensive check laga rakha hai?
- [ ] `UPLOAD_ERR_OK` se compare kiya?
- [ ] Max size **aur** zero-size dono check kiye?
- [ ] `finfo` se real MIME type nikala, extension se nahi trust kiya?
- [ ] `getimagesize()` se double-check kiya?
- [ ] Final extension MIME type se derive kiya, filename se nahi?