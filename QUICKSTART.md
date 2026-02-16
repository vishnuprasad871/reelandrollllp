# Quick Start Guide

## Running the Project Locally

### Prerequisites
- Docker Desktop installed and running

### Steps

1. **Open Terminal/PowerShell** in the project directory:
   ```bash
   cd c:\Myprojects\ReelandRoll
   ```

2. **Start Docker containers**:
   ```bash
   docker-compose up -d
   ```
   
   This will start:
   - PHP/Apache server on port 8080
   - MySQL database on port 3306
   - PHPMyAdmin on port 8081

3. **Wait for initialization** (first time only, ~30 seconds)
   The database will automatically:
   - Create tables
   - Add admin user
   - Import sample gallery images

4. **Access the applications**:
   - Website: http://localhost:8080
   - Gallery: http://localhost:8080/gallery.html
   - Admin Panel: http://localhost:8080/admin
   - Database Manager: http://localhost:8081

5. **Login to Admin**:
   ```
   Username: admin
   Password: admin123
   ```

6. **Upload your first image**:
   - Drag and drop an image
   - Select a category (Wedding, Portrait, Event, or Landscape)
   - Add descriptive alt text
   - Click "Upload Image"
   - Visit gallery page to see it live!

### Stop the Project
```bash
docker-compose down
```

### View Logs (if troubleshooting)
```bash
docker-compose logs
```

### Reset Database (fresh start)
```bash
docker-compose down -v
docker-compose up -d
```

## Important Note on gallery.html

Due to a file editing limitation, you need to manually add one line to `gallery.html`:

**Open `gallery.html` and find line 181:**
```html
<script src="script.js"></script>
```

**Change it to:**
```html
<script src="gallery-api.js"></script>
<script src="script.js"></script>
```

Also, find line 75 and ensure the gallery grid has an ID:
```html
<div class="gallery-grid" id="galleryGrid">
```

This allows the gallery to load images from the database!

## Next Steps

1. Test the admin interface
2. Upload some test images
3. Check they appear on the gallery page
4. Review the README.md for full documentation
5. When ready for production, follow DEPLOYMENT.md

## Common Issues

**Docker won't start?**
- Ensure Docker Desktop is running
- Try: `docker-compose down` then `docker-compose up -d`

**Can't access localhost:8080?**
- Wait a minute for containers to fully start
- Check if port 8080 is already in use

**Images not uploading?**
- Check Docker logs: `docker-compose logs web`
- Verify you're logged into admin panel

**Gallery showing no images?**
- Make sure you added the `gallery-api.js` script (see note above)
- Check browser console for errors (F12)

---

**Need help?** Check the full README.md or DEPLOYMENT.md documentation.
