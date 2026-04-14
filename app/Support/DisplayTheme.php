<?php

namespace App\Support;

class DisplayTheme
{
    public static function themeVariables(?string $backgroundColor, ?string $textColor = null, ?string $cardBackgroundColor = null): array
    {
        $backgroundColor = self::normalizeHexColor($backgroundColor);
        $textColor = self::normalizeHexColorValue($textColor) ?? self::textColorFor($backgroundColor);
        $usesDarkText = $textColor === '#111827';

        $panelColor = self::mix($backgroundColor, $textColor, $usesDarkText ? 0.06 : 0.08);
        $cardColor = self::normalizeHexColorValue($cardBackgroundColor)
            ?? self::mix($backgroundColor, $textColor, $usesDarkText ? 0.1 : 0.14);
        $surfaceColor = self::mix($backgroundColor, $textColor, $usesDarkText ? 0.14 : 0.06);

        return [
            'backgroundColor' => $backgroundColor,
            'textColor' => $textColor,
            'mutedTextColor' => self::rgba($textColor, $usesDarkText ? 0.72 : 0.78),
            'borderColor' => self::rgba($textColor, $usesDarkText ? 0.12 : 0.08),
            'borderStrongColor' => self::rgba($textColor, $usesDarkText ? 0.2 : 0.16),
            'panelBackgroundColor' => self::rgba($panelColor, $usesDarkText ? 0.74 : 0.6),
            'cardBackgroundColor' => self::rgba($cardColor, $usesDarkText ? 0.86 : 0.92),
            'surfaceBackgroundColor' => self::rgba($surfaceColor, $usesDarkText ? 0.88 : 0.9),
            'marqueeBackgroundColor' => self::rgba(self::mix($backgroundColor, $textColor, $usesDarkText ? 0.08 : 0.1), $usesDarkText ? 0.92 : 0.95),
            'galleryOverlayColor' => self::rgba(self::mix($backgroundColor, $textColor, $usesDarkText ? 0.18 : 0.28), $usesDarkText ? 0.88 : 0.92),
            'shadowColor' => $usesDarkText ? 'rgba(15, 23, 42, 0.16)' : 'rgba(0, 0, 0, 0.3)',
        ];
    }

    public static function normalizeHexColor(?string $color, string $fallback = '#0b0d18'): string
    {
        $fallback = self::normalizeHexColorValue($fallback) ?? '#0B0D18';

        return self::normalizeHexColorValue($color) ?? $fallback;
    }

    public static function textColorFor(?string $backgroundColor): string
    {
        $backgroundColor = self::normalizeHexColor($backgroundColor);
        $lightText = '#F8FAFC';
        $darkText = '#111827';

        return self::contrastRatio($backgroundColor, $lightText) >= self::contrastRatio($backgroundColor, $darkText)
            ? $lightText
            : $darkText;
    }

    public static function rgba(string $hexColor, float $alpha): string
    {
        [$red, $green, $blue] = self::hexToRgb($hexColor);
        $alpha = max(0, min(1, $alpha));

        return sprintf('rgba(%d, %d, %d, %.3F)', $red, $green, $blue, $alpha);
    }

    public static function mix(string $baseColor, string $targetColor, float $ratio): string
    {
        [$baseRed, $baseGreen, $baseBlue] = self::hexToRgb($baseColor);
        [$targetRed, $targetGreen, $targetBlue] = self::hexToRgb($targetColor);

        $ratio = max(0, min(1, $ratio));

        $red = (int) round($baseRed + (($targetRed - $baseRed) * $ratio));
        $green = (int) round($baseGreen + (($targetGreen - $baseGreen) * $ratio));
        $blue = (int) round($baseBlue + (($targetBlue - $baseBlue) * $ratio));

        return sprintf('#%02X%02X%02X', $red, $green, $blue);
    }

    private static function contrastRatio(string $firstColor, string $secondColor): float
    {
        $firstLuminance = self::relativeLuminance($firstColor);
        $secondLuminance = self::relativeLuminance($secondColor);

        $lighter = max($firstLuminance, $secondLuminance);
        $darker = min($firstLuminance, $secondLuminance);

        return ($lighter + 0.05) / ($darker + 0.05);
    }

    private static function relativeLuminance(string $hexColor): float
    {
        [$red, $green, $blue] = self::hexToRgb($hexColor);

        $channels = array_map(function (int $channel): float {
            $channel = $channel / 255;

            return $channel <= 0.03928
                ? $channel / 12.92
                : (($channel + 0.055) / 1.055) ** 2.4;
        }, [$red, $green, $blue]);

        return ($channels[0] * 0.2126) + ($channels[1] * 0.7152) + ($channels[2] * 0.0722);
    }

    private static function hexToRgb(string $hexColor): array
    {
        $hexColor = ltrim(self::normalizeHexColor($hexColor), '#');

        return [
            hexdec(substr($hexColor, 0, 2)),
            hexdec(substr($hexColor, 2, 2)),
            hexdec(substr($hexColor, 4, 2)),
        ];
    }

    private static function normalizeHexColorValue(?string $color): ?string
    {
        if (!is_string($color)) {
            return null;
        }

        $color = trim($color);
        if ($color === '') {
            return null;
        }

        if (!str_starts_with($color, '#')) {
            $color = '#' . $color;
        }

        if (!preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $color)) {
            return null;
        }

        if (strlen($color) === 4) {
            $color = sprintf(
                '#%s%s%s%s%s%s',
                $color[1],
                $color[1],
                $color[2],
                $color[2],
                $color[3],
                $color[3]
            );
        }

        return strtoupper($color);
    }
}
