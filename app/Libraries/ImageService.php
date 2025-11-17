<?php

namespace App\Libraries;

use CodeIgniter\Images\Image;

/**
 * Image Service
 * Handles image processing: resizing, thumbnail creation, watermarking (text and image), and dimension retrieval
 */
class ImageService
{
    protected $image;
    protected $config;

    /**
     * Initialize image service with CodeIgniter image library
     */
    public function __construct()
    {
        $this->image = \Config\Services::image();
        $this->config = config('Images');
    }

    /**
     * Process and resize uploaded image
     * 
     * @param string $sourcePath Full path to source image
     * @param string $destinationPath Full path to save processed image
     * @param int $maxWidth Maximum width
     * @param int $maxHeight Maximum height
     * @param int $quality JPEG quality (1-100)
     * @return bool Success status
     */
    public function resizeImage($sourcePath, $destinationPath, $maxWidth = 800, $maxHeight = 800, $quality = 85)
    {
        try {
            $this->image->withFile($sourcePath)
                ->resize($maxWidth, $maxHeight, true, 'width')
                ->save($destinationPath, $quality);
            
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Image resize failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create a thumbnail from an image
     * 
     * @param string $sourcePath Full path to source image
     * @param string $destinationPath Full path to save thumbnail
     * @param int $width Thumbnail width
     * @param int $height Thumbnail height
     * @param string $position Fit position: 'center', 'top-left', 'top', etc.
     * @param int $quality JPEG quality (1-100)
     * @return bool Success status
     */
    public function createThumbnail($sourcePath, $destinationPath, $width = 200, $height = 200, $position = 'center', $quality = 80)
    {
        try {
            $this->image->withFile($sourcePath)
                ->fit($width, $height, $position)
                ->save($destinationPath, $quality);
            
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Thumbnail creation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Process uploaded image file with resize, watermark, and thumbnail creation
     * 
     * @param object $file Uploaded file object
     * @param string $uploadPath Directory path for uploads
     * @param array $options Processing options
     * @return array|false Returns array with 'original' and 'thumbnail' paths, or false on failure
     */
    public function processUploadedImage($file, $uploadPath, $options = [])
    {
        $defaultOptions = [
            'maxWidth' => 800,
            'maxHeight' => 800,
            'thumbWidth' => 200,
            'thumbHeight' => 200,
            'quality' => 85,
            'thumbQuality' => 80,
            'createThumbnail' => true,
            'watermark' => false,
            'watermarkType' => 'text', // 'text' or 'image'
            'watermarkText' => 'BrewKaholic',
            'watermarkImage' => null, // Path to watermark image file
            'watermarkPosition' => 'bottom-right',
            'watermarkFontSize' => 16,
            'watermarkColor' => '#FFFFFF',
            'watermarkOpacity' => 50
        ];

        $options = array_merge($defaultOptions, $options);

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return false;
        }

        $newName = $file->getRandomName();
        $originalPath = $uploadPath . $newName;

        // Move uploaded file
        if (!$file->move($uploadPath, $newName)) {
            return false;
        }

        $result = [
            'original' => $newName,
            'thumbnail' => null
        ];

        // Resize original image
        if (!$this->resizeImage($originalPath, $originalPath, $options['maxWidth'], $options['maxHeight'], $options['quality'])) {
            // If resize fails, still return the original
            return $result;
        }

        // Add watermark if requested
        if ($options['watermark']) {
            if ($options['watermarkType'] === 'text') {
                $this->addTextWatermark(
                    $originalPath,
                    $originalPath,
                    $options['watermarkText'],
                    $options['watermarkPosition'],
                    $options['watermarkFontSize'],
                    $options['watermarkColor'],
                    $options['watermarkOpacity'],
                    $options['quality']
                );
            } elseif ($options['watermarkType'] === 'image' && $options['watermarkImage']) {
                $this->addImageWatermark(
                    $originalPath,
                    $originalPath,
                    $options['watermarkImage'],
                    $options['watermarkPosition'],
                    $options['watermarkOpacity'],
                    $options['quality']
                );
            }
        }

        // Create thumbnail if requested
        if ($options['createThumbnail']) {
            $thumbName = 'thumb_' . $newName;
            $thumbPath = $uploadPath . $thumbName;
            
            if ($this->createThumbnail($originalPath, $thumbPath, $options['thumbWidth'], $options['thumbHeight'], 'center', $options['thumbQuality'])) {
                $result['thumbnail'] = $thumbName;
            }
        }

        return $result;
    }

    /**
     * Add text watermark to an image
     * 
     * Handles color normalization (converts arrays to hex strings if needed), maps position strings
     * to CodeIgniter alignment constants, and applies text watermark with shadow.
     * 
     * @param string $sourcePath Full path to source image
     * @param string $destinationPath Full path to save watermarked image
     * @param string $text Watermark text
     * @param string $position Position: 'bottom-right', 'bottom-left', 'top-right', 'top-left', 'center'
     * @param int $fontSize Font size (default: 16)
     * @param string|array $color Text color in hex format (default: '#FFFFFF') or RGB array
     * @param int $opacity Opacity 0-100 (default: 50)
     * @param int $quality JPEG quality (1-100)
     * @return bool Success status
     */
    public function addTextWatermark($sourcePath, $destinationPath, $text, $position = 'bottom-right', $fontSize = 16, $color = '#FFFFFF', $opacity = 50, $quality = 85)
    {
        try {
            $image = $this->image->withFile($sourcePath);
            $width = $image->getWidth();
            $height = $image->getHeight();
            
            // Ensure color is a string (handle arrays that might come from form)
            if (is_array($color)) {
                // If it's an array, convert back to hex string
                if (isset($color[0]) && isset($color[1]) && isset($color[2])) {
                    $color = sprintf('#%02x%02x%02x', $color[0], $color[1], $color[2]);
                } else {
                    $color = '#FFFFFF'; // Default to white if invalid array
                }
            }
            
            // Ensure color is a string and properly formatted
            $color = is_string($color) ? $color : '#FFFFFF';
            $color = trim($color, '# '); // Remove # and spaces for CodeIgniter
            
            // Convert hex color to RGB for shadow color
            $rgb = $this->hexToRgb('#' . $color);
            
            // Map position to CodeIgniter's alignment constants
            $hAlign = 'center';
            $vAlign = 'bottom';
            
            switch ($position) {
                case 'bottom-right':
                    $hAlign = 'right';
                    $vAlign = 'bottom';
                    break;
                case 'bottom-left':
                    $hAlign = 'left';
                    $vAlign = 'bottom';
                    break;
                case 'top-right':
                    $hAlign = 'right';
                    $vAlign = 'top';
                    break;
                case 'top-left':
                    $hAlign = 'left';
                    $vAlign = 'top';
                    break;
                case 'center':
                    $hAlign = 'center';
                    $vAlign = 'center';
                    break;
            }
            
            // Add text watermark using CodeIgniter's text method
            // CodeIgniter expects color as hex string (without #), not RGB array
            $image->text($text, [
                'color'      => $color, // Pass as hex string (without #)
                'shadowColor' => $color, // Use same color for shadow
                'opacity'    => $opacity / 100,
                'withShadow' => true,
                'hAlign'     => $hAlign,
                'vAlign'     => $vAlign,
                'fontSize'   => $fontSize,
            ])
            ->save($destinationPath, $quality);
            
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Watermark addition failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Add image watermark to an image
     * 
     * Calculates watermark position coordinates based on source image dimensions and watermark size.
     * Uses 20px padding from edges. Falls back to bottom-right if invalid position provided.
     * 
     * @param string $sourcePath Full path to source image
     * @param string $destinationPath Full path to save watermarked image
     * @param string $watermarkPath Full path to watermark image
     * @param string $position Position: 'bottom-right', 'bottom-left', 'top-right', 'top-left', 'center'
     * @param int $opacity Opacity 0-100 (default: 50)
     * @param int $quality JPEG quality (1-100)
     * @return bool Success status
     */
    public function addImageWatermark($sourcePath, $destinationPath, $watermarkPath, $position = 'bottom-right', $opacity = 50, $quality = 85)
    {
        try {
            if (!file_exists($watermarkPath)) {
                log_message('error', 'Watermark image not found: ' . $watermarkPath);
                return false;
            }
            
            $image = $this->image->withFile($sourcePath);
            $watermark = $this->image->withFile($watermarkPath);
            
            $sourceWidth = $image->getWidth();
            $sourceHeight = $image->getHeight();
            $watermarkWidth = $watermark->getWidth();
            $watermarkHeight = $watermark->getHeight();
            
            // Calculate position coordinates
            $padding = 20;
            $x = 0;
            $y = 0;
            
            switch ($position) {
                case 'bottom-right':
                    $x = $sourceWidth - $watermarkWidth - $padding;
                    $y = $sourceHeight - $watermarkHeight - $padding;
                    break;
                case 'bottom-left':
                    $x = $padding;
                    $y = $sourceHeight - $watermarkHeight - $padding;
                    break;
                case 'top-right':
                    $x = $sourceWidth - $watermarkWidth - $padding;
                    $y = $padding;
                    break;
                case 'top-left':
                    $x = $padding;
                    $y = $padding;
                    break;
                case 'center':
                    $x = ($sourceWidth / 2) - ($watermarkWidth / 2);
                    $y = ($sourceHeight / 2) - ($watermarkHeight / 2);
                    break;
                default:
                    $x = $sourceWidth - $watermarkWidth - $padding;
                    $y = $sourceHeight - $watermarkHeight - $padding;
            }
            
            // Add image watermark
            $image->watermark($watermarkPath, $position, $opacity)
                ->save($destinationPath, $quality);
            
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Image watermark addition failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Convert hex color to RGB array
     * 
     * @param string $hex Hex color code (e.g., '#FFFFFF' or 'FFFFFF')
     * @return array RGB array ['r' => 255, 'g' => 255, 'b' => 255]
     */
    protected function hexToRgb($hex)
    {
        $hex = str_replace('#', '', $hex);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        return ['r' => $r, 'g' => $g, 'b' => $b];
    }

    /**
     * Get image dimensions
     * 
     * @param string $imagePath Full path to image
     * @return array|false Returns array with 'width' and 'height', or false on failure
     */
    public function getImageDimensions($imagePath)
    {
        try {
            $image = $this->image->withFile($imagePath);
            return [
                'width' => $image->getWidth(),
                'height' => $image->getHeight()
            ];
        } catch (\Exception $e) {
            log_message('error', 'Failed to get image dimensions: ' . $e->getMessage());
            return false;
        }
    }
}
