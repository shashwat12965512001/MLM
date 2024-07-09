</div> <!-- nk-content -->
<div class="nk-footer"></div>
</div> <!-- nk-wrap -->
</div> <!-- nk-main -->
</div> <!-- nk-app-root -->
<?php
// Directory containing JavaScript files
$directory = '../js/';

// Scan the directory for JavaScript files
$jsFiles = glob($directory . '*.js');

// Loop through each JavaScript file and output HTML script tags
foreach ($jsFiles as $jsFile) {
	echo '<script src="' . $jsFile . '"></script>' . PHP_EOL;
}

$conn->close();
?>
</body>
</html>