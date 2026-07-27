# Krila – updated for PHP 8.5+

This is the original imageboard-style board modernised for the latest PHP (tested against 8.3–8.5 syntax & deprecations).

## Changes made
- `declare(strict_types=1);`
- No undefined variables (all initialised / null-coalesced)
- `pathinfo()` instead of `end(explode())`
- Safe handling of missing `$_SERVER['HTTP_REFERER']`, `$_POST`, `$_FILES`
- `str_starts_with` / `str_ends_with` (PHP 8.0+)
- `htmlspecialchars` on output to avoid XSS
- Proper upload error checking (`UPLOAD_ERR_*`)
- Empty `thread/` directory no longer causes warnings
- Tripcode generation still uses nested `crypt()` (salt always supplied → no PHP 8.4 notice)

## Directory layout
```
krila/
├── index.php
├── post.php
├── postcomment.php
├── thread.php
├── static/
│   ├── krila.css
│   └── logo.png          ← replace with your real logo
├── thread/               ← empty; posts create .txt files here
└── cdn/                  ← uploaded images land here
```

Replace `static/logo.png` with the original logo.
