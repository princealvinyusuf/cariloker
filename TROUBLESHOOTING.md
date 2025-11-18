# Troubleshooting Job Import Staging Feature

## Quick Fix: Clear All Caches

Run these commands in your terminal:

```bash
cd /Users/macossinema/Desktop/cariloker

# Clear all Laravel caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan clear-compiled

# Or run the script:
./clear-cache.sh
```

## Common Issues & Solutions

### 1. **Feature Not Working After Code Changes**

**Solution:** Clear Laravel caches (see above)

Laravel caches routes, config, and views. After code changes, you must clear these caches.

### 2. **"Method Not Found" or Route Errors**

**Check:**
- Routes are registered: `php artisan route:list | grep import`
- Controller method exists: Check `app/Http/Controllers/Admin/JobImportController.php`
- Clear route cache: `php artisan route:clear`

### 3. **Progress Bar Not Updating**

**Check:**
- Browser console for JavaScript errors (F12 → Console tab)
- Network tab for failed requests (F12 → Network tab)
- Check if `/admin/jobs/import/progress` returns JSON
- Clear browser cache (Ctrl+Shift+Delete)

### 4. **"403 Forbidden" Error**

**Solution:** Make sure you're logged in as an admin user:
- Check `users` table: `role` column should be `'admin'`
- Logout and login again
- Check session is valid

### 5. **No Data Processing**

**Check:**
- `job_imports` table has data: `SELECT COUNT(*) FROM job_imports;`
- Data columns match expected format
- Check Laravel logs: `storage/logs/laravel.log`

### 6. **Database Errors**

**Check:**
- All migrations are run: `php artisan migrate:status`
- Database connection is working
- Tables exist: `job_imports`, `job_listings`, `companies`, `locations`, etc.

### 7. **JavaScript Errors**

**Check:**
- Open browser DevTools (F12)
- Check Console tab for errors
- Check Network tab for failed requests
- Verify CSRF token is present in page

## Debugging Steps

1. **Check Laravel Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Test Routes Manually:**
   ```bash
   php artisan route:list --name=admin.jobs.import
   ```

3. **Check Cache Status:**
   ```bash
   php artisan cache:table  # If using database cache
   ```

4. **Verify Database:**
   ```sql
   SELECT COUNT(*) FROM job_imports;
   SELECT * FROM job_imports LIMIT 1;
   ```

5. **Test Progress Endpoint:**
   - Visit: `http://your-domain/admin/jobs/import/progress`
   - Should return JSON: `{"processed":0,"total":0,"running":false}`

## Still Not Working?

1. **Check browser console** for JavaScript errors
2. **Check Laravel logs** (`storage/logs/laravel.log`) for PHP errors
3. **Verify you're logged in** as admin user
4. **Clear browser cache** completely
5. **Try in incognito/private mode** to rule out browser extensions

## Manual Testing

1. Go to `/admin/jobs/import`
2. Open browser DevTools (F12)
3. Click "Process Staging Data"
4. Watch Console and Network tabs
5. Check for any errors or failed requests

