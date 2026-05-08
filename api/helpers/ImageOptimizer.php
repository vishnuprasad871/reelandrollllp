<?php
/**
 * ImageOptimizer
 * Resizes images to a sensible web max and converts to WebP when available.
 * Called after upload — operates in-place on the saved file.
 */
class ImageOptimizer
{
    // Max dimension on either axis — large enough for fullscreen, small enough to load fast
    const MAX_WIDTH    = 1920;
    const MAX_HEIGHT   = 1920;
    const JPEG_QUALITY = 85;   // 85 is visually lossless for photos
    const WEBP_QUALITY = 82;   // WebP 82 ≈ JPEG 90, roughly half the file size
    const PNG_COMPRESS = 7;    // 0-9; 7 is a good speed/size balance

    /**
     * Optimize a saved image file.
     * Resizes if larger than MAX, then converts to WebP (if GD supports it).
     *
     * @param  string $filePath  Absolute path to the saved file
     * @param  string $filename  Just the filename (no directory)
     * @return string            The (possibly new) filename — update your DB record with this
     */
    public static function optimize(string $filePath, string $filename): string
    {
        if (!extension_loaded('gd')) {
            return $filename;  // GD not available — skip silently
        }

        $info = @getimagesize($filePath);
        if (!$info) {
            return $filename;
        }

        list($origW, $origH, $type) = $info;

        // Load source image based on type
        $src = null;
        switch ($type) {
            case IMAGETYPE_JPEG:
                $src = @imagecreatefromjpeg($filePath);
                break;
            case IMAGETYPE_PNG:
                $src = @imagecreatefrompng($filePath);
                break;
            case IMAGETYPE_GIF:
                $src = @imagecreatefromgif($filePath);
                break;
            case IMAGETYPE_WEBP:
                $src = @imagecreatefromwebp($filePath);
                break;
        }

        if (!$src) {
            return $filename;
        }

        // Calculate target dimensions (preserve aspect ratio)
        list($newW, $newH) = self::calcDimensions($origW, $origH);

        // Resize if image is too large
        if ($newW < $origW || $newH < $origH) {
            $dst = imagecreatetruecolor($newW, $newH);

            // Preserve transparency for PNG / GIF
            if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
                imagefilledrectangle($dst, 0, 0, $newW, $newH, $transparent);
            }

            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
            imagedestroy($src);
            $src = $dst;
        }

        $dir = dirname($filePath) . DIRECTORY_SEPARATOR;

        // ── Attempt WebP conversion (best quality-to-size ratio) ──────────
        if (function_exists('imagewebp') && $type !== IMAGETYPE_GIF) {
            $webpFilename = preg_replace('/\.[^.]+$/', '.webp', $filename);
            $webpPath     = $dir . $webpFilename;

            if (@imagewebp($src, $webpPath, self::WEBP_QUALITY)) {
                imagedestroy($src);
                // Remove the original non-webp file if filename changed
                if ($webpFilename !== $filename) {
                    @unlink($filePath);
                }
                return $webpFilename;
            }
        }

        // ── Fallback: re-save in original format (still benefits from resize) ──
        switch ($type) {
            case IMAGETYPE_JPEG:
                @imagejpeg($src, $filePath, self::JPEG_QUALITY);
                break;
            case IMAGETYPE_PNG:
                @imagepng($src, $filePath, self::PNG_COMPRESS);
                break;
            // GIF and WebP: keep as-is (resize already applied to $src above)
        }

        imagedestroy($src);
        return $filename;
    }

    /**
     * Calculate new dimensions keeping aspect ratio within MAX_WIDTH × MAX_HEIGHT.
     */
    private static function calcDimensions(int $w, int $h): array
    {
        if ($w <= self::MAX_WIDTH && $h <= self::MAX_HEIGHT) {
            return [$w, $h];
        }

        $ratio = min(self::MAX_WIDTH / $w, self::MAX_HEIGHT / $h);
        return [(int)round($w * $ratio), (int)round($h * $ratio)];
    }
}
