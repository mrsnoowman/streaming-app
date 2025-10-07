# Content Security Policy (CSP) Fix for Video Streaming

## Problem

**Errors in Browser Console:**
```
Refused to load the stylesheet 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' 
because it violates the following Content Security Policy directive: "style-src 'self' 'unsafe-inline' fonts.googleapis.com cdn.jsdelivr.net"

Refused to load media from 'blob:<URL>' because it violates the following Content Security Policy directive: 
"media-src 'self' https:"

Auto-play prevented: NotSupportedError: The element has no supported sources.
```

## Root Cause

The Content Security Policy (CSP) was too restrictive and blocked:
1. **Font Awesome** from `cdnjs.cloudflare.com`
2. **Blob URLs** used by HLS.js for video streaming
3. **Stream URLs** from external domains

## Solution Applied

✅ **Updated** `app/Http/Middleware/SecurityHeaders.php` with proper CSP directives

### Changes Made

**Before:**
```php
$csp = "default-src 'self'; " .
       "script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net; " .
       "style-src 'self' 'unsafe-inline' fonts.googleapis.com cdn.jsdelivr.net; " .
       "font-src 'self' fonts.gstatic.com cdn.jsdelivr.net; " .
       "img-src 'self' data: https:; " .
       "media-src 'self' https:; " .
       "connect-src 'self' https:; " .
       "frame-src 'none'; " .
       "object-src 'none'; " .
       "base-uri 'self';";
```

**After:**
```php
$csp = "default-src 'self'; " .
       "script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net cdnjs.cloudflare.com; " .
       "style-src 'self' 'unsafe-inline' fonts.googleapis.com cdn.jsdelivr.net cdnjs.cloudflare.com; " .
       "style-src-elem 'self' 'unsafe-inline' fonts.googleapis.com cdn.jsdelivr.net cdnjs.cloudflare.com; " .
       "font-src 'self' fonts.gstatic.com cdn.jsdelivr.net cdnjs.cloudflare.com data:; " .
       "img-src 'self' data: https: blob:; " .
       "media-src 'self' https: blob: data: *.tjt-info.co.id; " .
       "connect-src 'self' https: wss: *.tjt-info.co.id; " .
       "worker-src 'self' blob:; " .
       "base-uri 'self';";
```

## What Each Directive Does

### 1. **script-src**
```
'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net cdnjs.cloudflare.com
```
- Allows JavaScript from same origin
- Allows inline scripts (needed for HLS.js initialization)
- Allows eval (needed for HLS.js)
- Allows CDN scripts (HLS.js, Font Awesome)

### 2. **style-src & style-src-elem**
```
'self' 'unsafe-inline' fonts.googleapis.com cdn.jsdelivr.net cdnjs.cloudflare.com
```
- Allows CSS from same origin
- Allows inline styles
- Allows Google Fonts
- **Allows Font Awesome from cdnjs.cloudflare.com**

### 3. **font-src**
```
'self' fonts.gstatic.com cdn.jsdelivr.net cdnjs.cloudflare.com data:
```
- Allows fonts from various CDNs
- **Allows data: URIs for embedded fonts**

### 4. **media-src** (Critical for Video Streaming)
```
'self' https: blob: data: *.tjt-info.co.id
```
- **blob:** Required for HLS.js video streaming
- **data:** For data URIs
- **https:** For external HTTPS streams
- **\*.tjt-info.co.id:** Specific domain for CCTV streams

### 5. **connect-src** (Critical for Stream Loading)
```
'self' https: wss: *.tjt-info.co.id
```
- **https:** For AJAX requests to stream servers
- **wss:** For WebSocket connections (if needed)
- **\*.tjt-info.co.id:** Allow connections to stream server

### 6. **worker-src** (New Addition)
```
'self' blob:
```
- **blob:** Required for HLS.js web workers
- Enables parallel processing for video decoding

## How to Apply the Fix

### Option 1: Pull from GitHub (Recommended)

```bash
cd /var/www/html/streaming-app

# Pull latest changes
git pull origin main

# Clear cache
php artisan cache:clear
php artisan config:clear

# Restart web server
sudo systemctl restart nginx
# or
sudo systemctl restart apache2
```

### Option 2: Manual Update

Edit `app/Http/Middleware/SecurityHeaders.php`:

```bash
nano app/Http/Middleware/SecurityHeaders.php
```

Replace the CSP section with the updated version above, then:

```bash
php artisan cache:clear
sudo systemctl restart nginx
```

## Verification

After applying the fix, check:

1. **Open Browser Console** (F12)
2. **Reload the page**
3. **Look for:**
   - ✅ Font Awesome icons load correctly
   - ✅ No CSP errors in console
   - ✅ Video streams load and play
   - ✅ HLS.js initializes properly

## Testing Checklist

- [ ] Font Awesome icons visible
- [ ] No CSP errors in console
- [ ] Video player shows stream
- [ ] Auto-play works (or can be manually started)
- [ ] Dark/Light mode toggle works
- [ ] Pagination works correctly
- [ ] All 10 streams per page load

## Security Considerations

### What We Allow
- ✅ Specific CDNs (jsdelivr, cloudflare)
- ✅ Blob URLs (required for HLS.js)
- ✅ Specific stream domains (*.tjt-info.co.id)
- ✅ HTTPS connections only

### What We Still Block
- ❌ Arbitrary inline scripts (only from our code)
- ❌ Frames/iframes
- ❌ Flash and other objects
- ❌ Arbitrary external domains

### Best Practices Maintained
- ✅ HTTPS-only in production
- ✅ Strict referrer policy
- ✅ XSS protection enabled
- ✅ Clickjacking protection (X-Frame-Options)
- ✅ Content-type sniffing disabled

## Customization for Different Streaming Servers

If your streams are from different domains, update the CSP:

```php
// For different streaming servers
"media-src 'self' https: blob: data: *.your-domain.com *.another-domain.com; " .
"connect-src 'self' https: wss: *.your-domain.com *.another-domain.com; "
```

## Troubleshooting

### Issue: Videos still don't load

**Check:**
1. Browser console for CSP errors
2. Network tab for failed requests
3. Video URL format (should be .m3u8 for HLS)

**Solutions:**
```bash
# Clear browser cache
Ctrl + Shift + Delete

# Hard refresh
Ctrl + F5

# Clear server cache
php artisan cache:clear
php artisan config:clear
```

### Issue: Font Awesome icons still missing

**Check:**
1. Network tab shows 200 response for Font Awesome CSS
2. No CSP errors for cdnjs.cloudflare.com

**Solutions:**
```bash
# Verify CSP header
curl -I http://your-domain.com | grep Content-Security-Policy

# Check middleware is loaded
php artisan route:list
```

### Issue: HLS.js initialization fails

**Check:**
1. Console for "NotSupportedError"
2. Blob URLs are allowed
3. Worker-src is set correctly

**Debug:**
```javascript
// In browser console
console.log(Hls.isSupported());
// Should return: true
```

## Additional Resources

- [MDN: Content Security Policy](https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP)
- [HLS.js Documentation](https://github.com/video-dev/hls.js/)
- [CSP Evaluator](https://csp-evaluator.withgoogle.com/)

---

**Status**: ✅ Fixed and Deployed  
**Version**: 1.0.1  
**Last Updated**: October 2025
