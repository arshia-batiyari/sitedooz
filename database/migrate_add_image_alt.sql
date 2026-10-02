-- اجرای این دستور برای اضافه کردن ستون image_alt به جدول posts
-- اگر قبلاً ستون وجود داشته باشد، خطا می‌دهد (نادیده بگیرید)
ALTER TABLE posts ADD COLUMN image_alt VARCHAR(255) NULL AFTER cover;
