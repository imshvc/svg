<?php

// Cleanup SVG by isolating SVG tag

$file = $argv[1] ?? null;
$output = $argv[2] ?? null;

if ($file === null) {
  die(
    'Cleanup SVG by isolating SVG tag' . PHP_EOL .
    'usage: svg-cleanup <required: svg input file> <optional: output file>' . PHP_EOL
  );
}

if (!file_exists($file)) {
  die('error: file not found: ' . $file . PHP_EOL);
}

if (is_dir($file)) {
  die('error: file is a directory: ' . $file . PHP_EOL);
}

if (filesize($file) == 0) {
  die('error: empty file: ' . $file . PHP_EOL);
}

$svg = @file_get_contents($file);

if ($svg === false) {
  die('error: failed to open file: ' . $file . PHP_EOL);
}

$svg_open = strpos($svg, '<svg');
$svg_close = strpos($svg, '</svg>');

if ($svg_open === false) {
  die('error: no opening svg tag: ' . $file . PHP_EOL);
}

if ($svg_close === false) {
  die('error: no closing svg tag: ' . $file . PHP_EOL);
}

$svg = substr($svg, $svg_open);
$offset = $svg_open - strlen('</svg>');

$svg = substr($svg, 0, $svg_close - $offset);

if ($output !== null) {
  $status = @file_put_contents($output, $svg);

  if ($status === false) {
    die('error: output write failed: ' . $output . PHP_EOL);
  }

  exit(0);
}

echo $svg;
exit(0);
