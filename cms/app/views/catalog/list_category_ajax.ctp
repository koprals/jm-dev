<?php foreach($data as $data):?>
<?php echo $data['Category']['name']?><a href="<?php echo $settings['cms_url']?>Catalog/Add/<?php echo $data['Category']['id']?>">Edit</a><br>
<?php endforeach;?>