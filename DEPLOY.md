# Deploy qr. (UI + API) on Render

## One-click (Blueprint)

1. Push this repo to GitHub/GitLab.
2. In [Render Dashboard](https://dashboard.render.com) → **New** → **Blueprint**.
3. Connect the repo; Render will use `render.yaml` and create a **Web Service** from the Dockerfile.
4. Deploy. The app will be at `https://<service-name>.onrender.com`.

## Manual

1. **New** → **Web Service**.
2. Connect your repo.
3. Set **Runtime** to **Docker** (Dockerfile path: `./Dockerfile`).
4. Leave **Start Command** empty (container uses `ENTRYPOINT`).
5. Create the service. Render builds the image and runs it; the app listens on port **10000** by default.

## Endpoints

- **UI:** `https://<your-app>.onrender.com/` (serves `index.php`).
- **API:** `https://<your-app>.onrender.com/api.php?data=Hello&level=M&size=5`  
  Params: `data` (required), `level` (L|M|Q|H), `size` (1–10), `fg`, `bg` (hex without #), `dl=1` to force download.

## Notes

- The Docker image uses **PHP 8.2-FPM** + **nginx**, with the **GD** extension for the API.
- `temp/` is created in the image and is writable for API-generated PNGs.
- First load can be slow due to Render free-tier spin-down; subsequent requests are faster.
