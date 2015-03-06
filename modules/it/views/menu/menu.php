<ul>
	<?php foreach ($menu as $item) : ?>
		<li<?php echo $item->get_css_class() ?>>
			<a href="<?php echo $item->url ?>"><?php echo $item->title ?></a>
		</li>
	<?php endforeach ?>
</ul>