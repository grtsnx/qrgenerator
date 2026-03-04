# Contributing to PHP QR Code

Thank you for your interest in contributing. This project is licensed under the [GNU AGPL v3](LICENSE). By contributing, you agree that your contributions will be licensed under the same license.

## How to contribute

### Reporting bugs

- Open an issue describing the problem, PHP version, and steps to reproduce.
- If possible, include a minimal code sample that triggers the bug.

### Suggesting features

- Open an issue with a clear description of the feature and use case.
- Discussion may happen in the issue before any code is written.

### Code contributions

1. **Fork the repository** and create a branch from the default branch (e.g. `main` or `master`).
2. **Make your changes** in line with existing style (indentation, naming, comments).
3. **Test** that the library still generates valid QR codes (e.g. with the examples in `INSTALL` or via `api.php`).
4. **Commit** with clear, concise messages.
5. **Open a pull request** describing what you changed and why.

### What to touch

- **Library core** (`phpqrcode.php`, `qrlib.php`, and related modules): fix bugs, improve performance or correctness, or add configuration options. Keep compatibility in mind.
- **API / web** (`api.php`, `index.php`): improvements and fixes are welcome; larger UX changes are best discussed in an issue first.
- **Docs** (`README.md`, `INSTALL`, this file): typo fixes, clearer examples, and updated requirements are always welcome.

### Code style

- Use the existing style in the file you edit (spacing, brace placement, naming).
- Prefer clarity over brevity; keep comments accurate if you change behavior.

### License

By submitting a pull request, you agree that your contribution is licensed under the GNU Affero General Public License v3.0 (or later), consistent with this project. You must have the right to license your work under the AGPL.

## Questions

If you have questions about contributing or the license, please open an issue so maintainers and others can help.
