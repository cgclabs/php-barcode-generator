<?php

namespace Picqer\Barcode;

class BarcodeGeneratorSVG extends BarcodeGenerator
{

    /**
     * Return a SVG string representation of barcode.
     *
     * @param $code (string) code to print
     * @param $type (const) type of barcode
     * @param $widthFactor (int) Minimum width of a single bar in user units.
     * @param $totalHeight (int) Height of barcode in user units.
     * @param $color (string) Foreground color (in SVG format) for bar elements (background is transparent).
     * @return string SVG code.
     * @public
     */
    public function getBarcode($code, $type, $widthFactor = 2, $totalHeight = 30, $color = 'black', $paddingHorz = 0, $paddingVert = 0, $footerSize = 0, $footerFontSize = 25)
    {
        $barcodeData = $this->getBarcodeData($code, $type);

        // replace table for special characters
        $repstr = array("\0" => '', '&' => '&amp;', '<' => '&lt;', '>' => '&gt;');

        $svg = '<?xml version="1.0" standalone="no" ?>' . "\n";
        $svg .= '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">' . "\n";
        $svg .= '<svg width="' . round(($barcodeData['maxWidth'] * $widthFactor) + ($paddingHorz * 2), 3) . '" height="' . ($totalHeight + $footerSize + ($paddingVert * 2)) . '" version="1.1" xmlns="http://www.w3.org/2000/svg">' . "\n";
        $svg .= "\t" . '<desc>' . strtr($barcodeData['code'], $repstr) . '</desc>' . "\n";
        $svg .= "\t" . '<g id="bars" fill="' . $color . '" stroke="none">' . "\n";
        // print bars
        $positionHorizontal = $paddingHorz;
        foreach ($barcodeData['bars'] as $bar) {
            $barWidth = round(($bar['width'] * $widthFactor), 3);
            $barHeight = round(($bar['height'] * $totalHeight / $barcodeData['maxHeight']), 3);
            if ($bar['drawBar']) {
                $positionVertical = round(($bar['positionVertical'] * $totalHeight / $barcodeData['maxHeight']), 3) + $paddingVert;
                // draw a vertical bar
                $svg .= "\t\t" . '<rect x="' . $positionHorizontal . '" y="' . $positionVertical . '" width="' . $barWidth . '" height="' . $barHeight . '" />' . "\n";
            }
            $positionHorizontal += $barWidth;
        }
        if ($footerSize > 0) {
            $footer = '<text style="font-size:'.$footerFontSize.'px;" text-anchor="middle" x="'.(($positionHorizontal + $paddingHorz)/2).'" y="'.($totalHeight + $footerSize + $paddingVert).'">'.$code.'</text>';
        } else {
            $footer = '';
        }
        $svg .= "\t" . $footer . '</g>' . "\n";
        $svg .= '</svg>' . "\n";

        return $svg;
    }
}
