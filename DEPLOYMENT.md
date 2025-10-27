# Deployment Guide - TicketFlow Twig

This guide will help you deploy your Twig/PHP application to various platforms.

## 🚂 Railway.app (Recommended - Easiest)

Railway is a modern deployment platform with excellent PHP support.

### Prerequisites
- GitHub account
- Railway account (free tier available at https://railway.app)

### Steps

1. **Prepare Your Project**
   
   Create a `Procfile` in your root directory:
   ```
   web: php -S 0.0.0.0:$PORT
   ```

2. **Push to GitHub**
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git remote add origin YOUR_GITHUB_REPO_URL
   git push -u origin main
   ```

3. **Deploy on Railway**
   - Go to https://railway.app
   - Click "Start a New Project"
   - Select "Deploy from GitHub repo"
   - Choose your repository
   - Railway will auto-detect PHP and deploy!

4. **Access Your App**
   - Railway will provide a URL like: `https://your-app.railway.app`
   - Click the generated URL to access your application

### Configuration
Railway auto-detects PHP apps. No additional configuration needed!

---

## 🟣 Heroku

### Prerequisites
- Heroku account (https://heroku.com)
- Heroku CLI installed

### Steps

1. **Create Files**

   Create `Procfile`:
   ```
   web: php -S 0.0.0.0:$PORT
   ```

   Create `.buildpacks`:
   ```
   https://github.com/heroku/heroku-buildpack-php
   ```

2. **Deploy**
   ```bash
   heroku login
   heroku create your-app-name
   git push heroku main
   heroku open
   ```

---

## 🌐 Traditional PHP Hosting (InfinityFree, 000webhost)

### Steps

1. **Export Your Project**
   - Zip your entire project folder
   - Exclude `vendor/` folder to save space

2. **Upload via FTP**
   - Use FileZilla or your host's file manager
   - Upload to `public_html` or `htdocs`

3. **Install Composer Dependencies**
   - Access your host's terminal (if available)
   - Run: `composer install`
   - Or upload `vendor/` folder manually

4. **Update Paths**
   - If in subdirectory, update links in templates:
   - Example: `/css/style.css` → `/subfolder/css/style.css`

5. **Test**
   - Visit: `https://yoursite.com` or `https://yoursite.com/subfolder`

---

## 🐳 Docker (Advanced)

### Create Dockerfile

```dockerfile
FROM php:8.1-apache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . /var/www/html/

# Install dependencies
RUN cd /var/www/html && composer install --no-dev

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
```

### Build and Run
```bash
docker build -t ticketflow-twig .
docker run -p 8000:80 ticketflow-twig
```

---

## ✅ Post-Deployment Checklist

After deploying to any platform:

- [ ] Test landing page loads
- [ ] Test login/signup functionality
- [ ] Create a test ticket
- [ ] Edit and delete a ticket
- [ ] Check responsive design on mobile
- [ ] Verify all CSS loads correctly
- [ ] Test logout functionality
- [ ] Check browser console for errors

---

## 🔧 Troubleshooting

### CSS Not Loading
**Problem**: Styles missing after deployment

**Solutions**:
1. Check CSS path in `layout.twig`:
   ```twig
   <link rel="stylesheet" href="/css/style.css">
   ```

2. If in subdirectory, use relative path:
   ```twig
   <link rel="stylesheet" href="./css/style.css">
   ```

3. Check file permissions (755 for folders, 644 for files)

### 500 Internal Server Error
**Problem**: Server error on page load

**Solutions**:
1. Check PHP error logs
2. Ensure Composer dependencies are installed
3. Verify PHP version (8.1+ required)
4. Check file permissions

### Sessions Not Working
**Problem**: Can't stay logged in

**Solutions**:
1. Ensure `session_start()` is in `config.php`
2. Check session directory permissions
3. On shared hosting, may need custom session path:
   ```php
   session_save_path('/tmp');
   session_start();
   ```

### 404 Errors
**Problem**: Pages not found

**Solutions**:
1. Check file names match URLs exactly
2. Use `.htaccess` if on Apache
3. Verify web server configuration

---

## 🌍 Environment-Specific Configuration

### Production Mode (Optional)

Update `config.php` for production:

```php
$twig = new \Twig\Environment($loader, [
    'cache' => '/tmp/twig_cache',  // Enable caching
    'debug' => false               // Disable debug
]);
```

### Development Mode

```php
$twig = new \Twig\Environment($loader, [
    'cache' => false,  // Disable caching
    'debug' => true    // Enable debug
]);
```

---

## 📊 Platform Comparison

| Platform | Free Tier | Difficulty | PHP Support | Best For |
|----------|-----------|------------|-------------|----------|
| Railway | Yes | Easy | Excellent | Quick deployment |
| Heroku | Limited | Medium | Good | Production apps |
| 000webhost | Yes | Easy | Good | Simple hosting |
| InfinityFree | Yes | Easy | Basic | Basic sites |
| DigitalOcean | No ($5/mo) | Hard | Excellent | Full control |

---

## 🎯 Recommended Approach

**For this project, I recommend Railway.app:**

✅ Easiest setup (5 minutes)  
✅ Auto-detects PHP  
✅ Free tier available  
✅ GitHub integration  
✅ Automatic HTTPS  
✅ Custom domains  

---

## 📞 Need Help?

If you encounter issues:
1. Check error logs
2. Verify all files uploaded correctly
3. Test locally first: `php -S localhost:8000`
4. Check platform-specific documentation

---

**Happy Deploying! 🚀**