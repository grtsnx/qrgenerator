# PHP QR Code

A pure-PHP QR Code 2D barcode generator. Generate QR codes from text, URLs, contact info, Wi‑Fi credentials, and more—with no external dependencies beyond the PHP GD extension.

## Features

- **Pure PHP** — No external binaries or services
- **AGPL-licensed** — Free software; network use requires source disclosure (see [LICENSE](LICENSE))
- **Flexible output** — PNG files, browser output, or raw matrix data for integration (e.g. TCPDF)
- **Configurable** — Error correction (L, M, Q, H), size, colors, cache options
- **Web app & API** — Includes a simple “qr.” web UI and a GET-based API for server-side generation

## Requirements

- **PHP** 5.x or 7.x+
- **PHP GD2** extension with PNG support (JPEG optional)

## Installation

1. Clone or download this repository.
2. Ensure the `cache` directory (if used) is writable.
3. For the merged single-file build, use `phpqrcode.php`. For the modular build, use `qrlib.php` (which pulls in the rest).

### Quick start (library)

```php
<?php
require_once 'qrlib.php';

// Save to file: data, filename, ECC level (L|M|Q|H), pixel size per module, margin
QRcode::png('https://example.com', 'qrcode.png', 'L', 4, 2);

// Output directly to browser (must be first/only output)
QRcode::png('Hello, QR!');
```

### Web interface

Open `index.php` in a browser. The “qr.” UI lets you enter URLs, text, email, phone, or Wi‑Fi details, choose colors and size, and download PNG/SVG (client-side generation uses a separate JS library; the PHP backend can be used for the API).

### API

`api.php` provides a simple HTTP API:

| Parameter | Description |
|-----------|-------------|
| `data`    | Content to encode (required) |
| `level`   | Error correction: `L`, `M`, `Q`, `H` (default: `M`) |
| `size`    | Module size 1–10 (default: 5) |
| `fg`      | Foreground color hex without `#` (default: `000000`) |
| `bg`      | Background color hex without `#` (default: `ffffff`) |
| `dl`      | `1` to send as download |

**Example:**  
`/api.php?data=https%3A%2F%2Fexample.com&level=M&size=5&fg=000000&bg=ffffff`

Returns a PNG image (or JSON error if `data` is missing).

## Project structure

| Path           | Description |
|----------------|-------------|
| `phpqrcode.php`| Single merged file (no external includes) |
| `qrlib.php`    | Main entry for modular build |
| `qrtools.php`  | Utilities, cache, benchmark, TCPDF helper |
| `api.php`      | HTTP API for PNG generation |
| `index.php`    | “qr.” web UI |
| `INSTALL`      | Extra configuration and TCPDF notes |
| `LICENSE`      | GNU AGPL v3 |

Other files (`qrconst.php`, `qrconfig.php`, `qrspec.php`, etc.) are part of the library or the merged bundle.

## Configuration

In the modular build, edit `qrconfig.php` (or the merged config section in `phpqrcode.php`): cache directory, logging, mask search behavior, and maximum PNG size. See comments there and in `INSTALL`.

## TCPDF integration

See `INSTALL` and the `bindings/tcpdf` directory (if present) for wiring PHP QR Code into TCPDF’s 2D barcode support.

## License and acknowledgments

- **License:** [GNU Affero General Public License v3.0](LICENSE) (AGPL-3.0). If you use this code over a network (e.g. in a web API), you must offer the corresponding source under the AGPL.
- **Original PHP QR Code:** Copyright (C) 2010 Dominik Dzienia.  
  Based on the C library **libqrencode** (LGPL) by Kentaro Fukuchi.  
  Reed–Solomon encoder by Phil Karn, KA9Q.  
- **QR Code** is a registered trademark of DENSO WAVE INCORPORATED.

## Contributing

Contributions are welcome. Please read [CONTRIBUTING.md](CONTRIBUTING.md) and [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md) before submitting changes.
