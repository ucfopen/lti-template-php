<?php $this->layout('template', ['title' => 'Welcome Page']) ?>
<p>Hi, I'm an LTI! User ID is <?= $this->e($launch_params)?></p>