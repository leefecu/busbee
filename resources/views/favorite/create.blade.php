{!! Form::open(array('url' => 'favorite', 'method' => 'POST', 'class' => 'form')) !!}

<div class="form-group">
  {!! Form::label('USER') !!}
  {!! Form::text('user_id', null,
      array('required',
      'class'=>'form-control')) !!}
</div>

<div class="form-group">
  {!! Form::label('Description') !!}
  {!! Form::text('description', null,
      array('required',
      'class'=>'form-control')) !!}
</div>

<div class="form-group">
  {!! Form::label('Type') !!}
  {!! Form::text('type', null,
      array('required',
      'class'=>'form-control')) !!}
</div>

<div class="form-group">
  {!! Form::label('Target_id') !!}
  {!! Form::text('target_id', null,
      array('required',
      'class'=>'form-control')) !!}
</div>

<div class="form-group">
  {!! Form::label('Target_name') !!}
  {!! Form::text('target_name', null,
      array('required',
      'class'=>'form-control')) !!}
</div>

{!! Form::submit('add Favorite') !!}

{!! Form::close() !!}