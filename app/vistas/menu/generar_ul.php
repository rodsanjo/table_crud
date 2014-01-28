<ul id="menu" class="menu">
	<?php 
	foreach ($datos['menu_items'] as $item) {
		echo "<li class='item'><a href='{$item['href']}' title='{$item['title']}'>{$item['texto_visualizado']}</a></li>";
	}
	?>
</ul>       