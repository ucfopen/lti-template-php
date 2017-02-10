<?php $this->layout('template', ['title' => 'Welcome Page']) ?>
<p>Hi, I'm an LTI! User ID is <?= $this->e($user_id)?>, Course ID is <?= $this->e($course_id)?>.</p>
