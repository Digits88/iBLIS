@extends("layout")
@section("content")
    <div>
        <ol class="breadcrumb">
          <li><a href="{{{URL::route('user.home')}}}">{{ trans('messages.home') }}</a></li>
          <li><a href="{{ URL::route('test.index') }}">{{ Lang::choice('messages.test',2) }}</a></li>
          <li class="active">{{ trans('messages.enter-test-results') }}</li>
        </ol>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading ">
            <div class="container-fluid">
                <div class="row less-gutter">
                    <div class="col-md-11">
                        <span class="glyphicon glyphicon-user"></span> {{ trans('messages.test-results') }}
                        @if($test->testType->instruments->count() > 0)
                        <div class="panel-btn">
                            <a class="btn btn-sm btn-info fetch-test-data" href="javascript:void(0)"
                                title="{{trans('messages.fetch-test-data-title')}}"
                                data-test-type-id="{{$test->testType->id}}"
                                data-url="{{URL::route('instrument.getResult')}}"
                                data-instrument-count="{{$test->testType->instruments->count()}}">
                                <span class="glyphicon glyphicon-plus-sign"></span>
                                {{trans('messages.fetch-test-data')}}
                            </a>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-1">
                        <a class="btn btn-sm btn-primary pull-right"  href="#" onclick="window.history.back();return false;"
                            alt="{{trans('messages.back')}}" title="{{trans('messages.back')}}">
                            <span class="glyphicon glyphicon-backward"></span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
        <!-- if there are creation errors, they will show here -->
            
            @if($errors->all())
                <div class="alert alert-danger">
                    {{ HTML::ul($errors->all()) }}
                </div>
            @endif
            {{ Form::open(array('route' => array('test.saveResults',$test->id), 'method' => 'POST',
                'id' => 'form-enter-results')) }}
                @foreach($test->testType->measures as $measure)
                    <div class="form-group">
                        <?php
                        $ans = "";
                        foreach ($test->testResults as $res) {
                            if($res->measure_id == $measure->id)$ans = $res->result;
                        }
                        $fieldName = "m_".$measure->id;
                        ?>
                        @if ( $measure->isNumeric() ) 
                            {{ Form::label($fieldName , $measure->name) }}
                            {{ Form::text($fieldName, $ans, array(
                                'class' => 'form-control result-interpretation-trigger',
                                'data-url' => URL::route('test.resultinterpretation'),
                                'data-age' => $test->visit->patient->dob,
                                'data-gender' => $test->visit->patient->gender,
                                'data-measureid' => $measure->id
                                ))
                            }}
                            <span class='units'>{{$measure->unit}}</span>
                        @elseif ( $measure->isAlphanumeric() || $measure->isAutocomplete() ) 
                            <?php
                            $measure_values = array();
                            $measure_values[] = '';
                            foreach ($measure->measureRanges as $range) {
                                $measure_values[$range->alphanumeric] = $range->alphanumeric;
                            }
                            ?>
                            {{ Form::label($fieldName , $measure->name) }}
                            {{ Form::select($fieldName, $measure_values, array_search($ans, $measure_values),
                                array('class' => 'form-control result-interpretation-trigger',
                                'data-url' => URL::route('test.resultinterpretation'),
                                'data-measureid' => $measure->id
                                )) 
                            }}
                        @elseif ( $measure->isFreeText() ) 
                            {{ Form::label($fieldName, $measure->name) }}
                            {{Form::text($fieldName, $ans, array('class' => 'form-control'))}}
                        @endif
                    </div>
                @endforeach
                <div class="form-group">
                    {{ Form::label('interpretation', trans('messages.interpretation')) }}
                    {{ Form::textarea('interpretation', $test->interpretation, 
                        array('class' => 'form-control result-interpretation', 'rows' => '2')) }}
                </div>
                <div class="form-group actions-row">
                    {{ Form::button('<span class="glyphicon glyphicon-save">
                        </span> '.trans('messages.save-test-results'),
                        array('class' => 'btn btn-default', 'onclick' => 'submit()')) }}
                </div>
            {{ Form::close() }}
        </div>
    </div>
@stop