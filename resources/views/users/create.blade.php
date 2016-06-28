{!! Form::open(array('url' => 'users', 'method' => 'POST', 'class' => 'form')) !!}

<div class="form-group">
  {!! Form::label('Name') !!}
  {!! Form::text('name', null,
      array('required',
      'class'=>'form-control',
      'placeholder' => 'Please type in Name')) !!}
</div>

<div class="form-group">
  {!! Form::label('E-mail') !!}
  {!! Form::text('email', null,
      array('required',
      'class'=>'form-control',
      'placeholder' => 'Please type in E-mail')) !!}
</div>

<div class="form-group">
  {!! Form::label('PassWord') !!}
  {!! Form::password('passwd', null,
      array('required',
      'class'=>'form-control',
      'placeholder' => 'please type in password')) !!}
</div>

{!! Form::submit('Create User') !!}

{!! Form::close() !!}