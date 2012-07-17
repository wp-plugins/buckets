<?php print_r($_POST); ?>
<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-posts-buckets"><br></div>
	<h2>Manage Sidebars</h2>
	<br /><br />
	<h3>Show sidebar fields on:</h3>
	<input type="checkbox" name="display[]" value="pages" />Pages<br />
	<input type="checkbox" name="display[]" value="posts" />Posts
	<br /><br />
	Exceptions (ID seperate with commas):<br />
	<input type="text" value="" name="exceptions" />
	<br /><br /><br />
	<h3>Create Sidebar</h3>
	<form method="post">
		Name: <br />
		<input type="text" name="name" value="" />
		<br /><br />
		<input type="submit" value="Create" name="create" />
	</form>

</div>


<?php

/*

If theres no sidebars show the form. 
If there are sidebars list them and show a button to Create More. 
Form will then popup in lightbox. 

Once a sidebar is created show the code need to paste into template to display it. <?php the_field('sidebar-slug'); ?>



Create a Meta Option and store all the ID's of the Sidebars there to pull them easier. 





*/

?>