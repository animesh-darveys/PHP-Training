# Documents (PDF) Upload Validation — Revision Notes (PHP)

## Poora Code (Reference)

```php
if (empty($documents) || !isset($documents['name']) || empty($documents['name'][0])) {
    $documentsError = 'Please select at least one file.';
} else {
    $maxSize = 1 * 1024 * 1024;

    foreach ($documents['name'] as $key => $fileName) {
        if ($documents['error'][$key] !== UPLOAD_ERR_OK) {
            $documentsError = "Error uploading file: $fileName";
            break;
        }

        if ($documents['size'][$key] > $maxSize) {
            $documentsError = "File must not exceed 1 MB: $fileName";
            break;
        }

        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($documents['tmp_name'][$key]);

        if ($mimeType !== 'application/pdf' || $extension !== 'pdf') {
            $documentsError = "Please select PDF files only: $fileName";
            break;
        }
    }
}

if (empty($documentsError)) {
    $uploadDirectory = 'uploads/documents/';

    foreach ($documents['name'] as $key => $fileName) {
        $newFileName = bin2hex(random_bytes(16)) . '.pdf';
        $destination = $uploadDirectory . $newFileName;

        if (!move_uploaded_file($documents['tmp_name'][$key], $destination)) {
            $documentsError = "Failed to upload file: $fileName";
            break;
        }
    }
}
```

---

## Image Validation se Sabse Bada Difference

Image ek **single file** thi (`$profile`), lekin documents ek **multiple files** field hai (`$documents`) — HTML mein aisa likha jata hai:

```html
<input type="file" name="documents[]" multiple>
```

Isliye `$_FILES['documents']` mein har key (`name`, `error`, `size`, `tmp_name`) ek **single value nahi, ek array** hoti hai:

```php
$documents['name']     = ['a.pdf', 'b.pdf', 'c.pdf'];
$documents['error']    = [0, 0, 0];
$documents['size']     = [50000, 120000, 30000];
$documents['tmp_name'] = ['/tmp/php1', '/tmp/php2', '/tmp/php3'];
```

**Yaad rakhna:** Ye 4 arrays **parallel** hote hain — matlab `$key = 0` sab jagah **same file** ko refer karta hai (`name[0]`, `error[0]`, `size[0]`, `tmp_name[0]` — sab ek hi file ki info hai).

---

## Step-by-Step Breakdown

### Step 1 — Kam se kam ek file select hui?
```php
empty($documents) || !isset($documents['name']) || empty($documents['name'][0])
```
| Condition | Kya check karta hai |
|---|---|
| `empty($documents)` | `$documents` field set hi nahi hui |
| `!isset($documents['name'])` | Defensive check — malformed data |
| `empty($documents['name'][0])` | Pehli file ka naam khali hai (matlab koi file select nahi hui) |

**Note:** Sirf `name[0]` check ho raha hai — assumption ye hai ki agar pehli file missing hai, toh koi file select nahi hui. (Agar 5 slots the aur beech ka koi ek missing ho, wo alag scenario hai — usually browser aise empty gaps nahi banata.)

---

### Step 2 — Loop shuru: Har file ko baari-baari check karo
```php
foreach ($documents['name'] as $key => $fileName) {
```
- `$key` = index number (0, 1, 2...)
- `$fileName` = us index ki original filename
- Is `$key` se hi baaki teeno arrays (`error`, `size`, `tmp_name`) se **same file** ki info milegi

---

### Step 3 — Upload error check (per-file)
```php
if ($documents['error'][$key] !== UPLOAD_ERR_OK) {
    $documentsError = "Error uploading file: $fileName";
    break;
}
```
- Har file ka apna alag `error` code hota hai (kyunki multi-file upload mein ek file fail ho sakti hai, doosri success)
- **`break`** = loop turant ruk jata hai — jaise hi ek file fail hoti hai, aage ki files check nahi hoti (fail-fast approach)

---

### Step 4 — Size check (per-file)
```php
if ($documents['size'][$key] > $maxSize) {
    $documentsError = "File must not exceed 1 MB: $fileName";
    break;
}
```
- Har file individually 1MB se kam honi chahiye
- Image wale code jaisa `size === 0` check yahan **missing hai** — chaho toh add kar sakte ho consistency ke liye

---

### Step 5 — Extension + MIME type dono check (Sabse Important) 🔒
```php
$extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($documents['tmp_name'][$key]);

if ($mimeType !== 'application/pdf' || $extension !== 'pdf') {
    $documentsError = "Please select PDF files only: $fileName";
    break;
}
```

**Do cheezein check ho rahi hain (dono zaroori):**
1. **`$extension !== 'pdf'`** — filename ka extension `.pdf` hona chahiye
2. **`$mimeType !== 'application/pdf'`** — real file content bhi PDF hona chahiye (`finfo` se verify)

**Yahan `||` (OR) use ho raha hai, matlab dono mein se koi ek bhi fail ho → reject:**
- Agar extension `.pdf` hai lekin content kuch aur hai → reject (fake PDF)
- Agar content PDF hai lekin extension kuch aur hai (jaise `.pdf.exe`) → reject

**Image validation se difference:** Image mein humne sirf **MIME type se extension decide** kiya tha (extension whitelist hata diya tha). Yahan **dono ko explicitly match** karwaya ja raha hai kyunki sirf **ek hi allowed type** hai (`pdf`), toh extension whitelist ki zaroorat nahi thi — bas dono ko cross-verify kar rahe hain.

---

### Step 6 — Validation loop khatam, ab dusra loop: Actual saving
```php
if (empty($documentsError)) {
    $uploadDirectory = 'uploads/documents/';

    foreach ($documents['name'] as $key => $fileName) {
        $newFileName = bin2hex(random_bytes(16)) . '.pdf';
        $destination = $uploadDirectory . $newFileName;

        if (!move_uploaded_file($documents['tmp_name'][$key], $destination)) {
            $documentsError = "Failed to upload file: $fileName";
            break;
        }
    }
}
```

**Important concept — Do Alag Loops Kyun?**

```
Loop 1 (Validation) → Sab files check karo, KUCH SAVE MAT KARO
        ↓
Sab pass ho gaye?
        ↓ Haan
Loop 2 (Saving) → Ab actually files move karo
```

**Agar ek hi loop mein validate + save karte (GALAT approach):**
```php
// BURA EXAMPLE - aisa mat karo
foreach ($documents['name'] as $key => $fileName) {
    // validate karo
    // turant save bhi kar do
}
```
- File 1 aur File 2 validate ho ke save ho jaati
- File 3 invalid nikalti → error
- Result: **File 1 aur 2 already save ho chuki hain**, lekin user ko error mila — **inconsistent/partial state** ban gaya

**Do-loop approach mein:**
- Pehle **sab files ache se check** hoti hain
- Agar **koi ek bhi fail** hui, toh **koi bhi file save nahi hoti**
- Sab-ya-kuch-nahi (all-or-nothing) — clean aur predictable behavior

---

### Step 7 — Random filename (same jaisa image mein tha)
```php
$newFileName = bin2hex(random_bytes(16)) . '.pdf';
```
- Extension yahan **hardcoded `.pdf`** hai — dynamic nahi, kyunki humne already upar validate kar diya hai ki file PDF hi hai
- `random_bytes(16)` + `bin2hex()` = unpredictable filename, collision-proof, original naam hide

---

## 🎯 Poora Flow (Ek Nazar Mein)

```
Kam se kam ek file select hui?
    ↓ Haan
── LOOP 1: Har file check karo ──
    Upload error OK tha?
        ↓ Haan
    Size 1MB se kam?
        ↓ Haan
    Extension = pdf AND MIME type = application/pdf?
        ↓ Haan (sab files ke liye)
── Sab pass? ──
    ↓ Haan
── LOOP 2: Ab save karo ──
    Random filename banao (.pdf)
    move_uploaded_file() se save karo
    ↓
✅ Saari files save ho gayi
```

---

## 🔑 Image vs Documents — Key Differences Table

| Cheez | Image (Single File) | Documents (Multiple Files) |
|---|---|---|
| Field structure | `$profile['error']` (direct value) | `$documents['error'][$key]` (array) |
| Loop | Nahi chahiye | `foreach` zaroori hai |
| Extension decide kaise hota | MIME type se derive (`$allowedMimeToExt`) | Extension ko explicitly match karwaya (`=== 'pdf'`) |
| Allowed types | 3 types (jpg/png/webp) | Sirf 1 type (pdf) |
| Save karne ka tareeka | Ek hi baar `move_uploaded_file()` | Loop mein baar-baar `move_uploaded_file()` |
| Validate-save pattern | Ek hi if-block mein | Do alag loops (validate first, save after) |

---

## Quick Checklist (Revision ke liye)

- [ ] `$documents['name'][0]` se check kiya ki kam se kam ek file hai?
- [ ] `foreach` mein `$key` use karke parallel arrays access kiye?
- [ ] Har file ka apna `error`, `size` alag se check kiya?
- [ ] Extension **aur** MIME type dono match karwaye (`||` condition se)?
- [ ] `break` laga ke fail-fast approach follow ki?
- [ ] Validation aur saving ke liye **do alag loops** use kiye (all-or-nothing)?
- [ ] Saving ke time random filename (`bin2hex(random_bytes(16))`) use kiya?
