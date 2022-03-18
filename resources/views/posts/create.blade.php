@extends('layouts.app')

@section('content')
    <h1>Publish a Post</h1>
    <hr />
    <form method="post" action="{{ url('/posts') }}">
        @csrf
        <div class="form-group">
            <label for="topic">Topic:</label>
            <select class="browser-default custom-select" value="" id="topic_id" name="topic_id">
                <option selected>Please select a topic</option>

                @foreach($topics as $topic)
                    <option value="{{ $topic->id }}" {{ (collect(old('topic_id'))->contains($topic->id)) ? 'selected' : '' }}>{{ $topic->name }}</option>
                @endforeach

            </select>
        </div>
        <div class="form-group">
            <label for="question">Question:</label>
            <input type="text" class="form-control" id="quastion" value="{{ old('question') }}" name="question">
        </div>


        <div class="form-group">
            <label for="time">Time:</label>
            <input type="number" class="form-control" id="time" name="time" value="{{ old('time') }}" placeholder="30" min="30" max="90">
        </div>

        <div class="form-group">
            <label for="title">Options:</label>
            <div class="input-group control-group">
              <input type="text" name="option[]" class="form-control" value="{{ isset(old('option')[0]) ? old('option')[0] : '' }}" placeholder="Option">
              <div class="input-group-btn">
                <button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
              </div>
            </div>
        </div>

        <!-- Copy Fields -->
        <div>
            <div class="hide" id="copy-fields">
                <div class="form-group">
                    <div class="control-group input-group" style="margin-top:10px">
                        <input type="text" name="option[]" class="form-control" value="{{ isset(old('option')[1]) ? old('option')[1] : '' }}" placeholder="Option">
                        <div class="input-group-btn">
                          <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="after-add-more"></div>

        @if(old('option') !== NULL)
            @if(count(old('option')) > 2)
                @for($i = 2;$i < count(old('option'));$i++)
                    <div>
                        <div class="hide" id="copy-fields">
                            <div class="form-group">
                                <div class="control-group input-group" style="margin-top:10px">
                                    <input type="text" name="option[]" class="form-control" value="{{ isset(old('option')[$i]) ? old('option')[$i] : '' }}" placeholder="Option">
                                    <div class="input-group-btn">
                                      <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            @endif
        @endif

        <div class="form-group">
            <button type="submit" class="btn btn-purple">Create</button>
        </div>
    </form>

    <script type="text/javascript">
        window.onload = function() {
            $(document).ready(function() {
                $(".add-more").click(function(){
                    if($(".control-group").length < 6) {
                        var html = $("#copy-fields").val('').parent().html();
                        $(".after-add-more").append("<div>" + html + "</div>");
                    }
                });

                $("body").on("click",".remove",function(){
                    if($(".control-group").length > 2) {
                       $(this).parents(".control-group").parent().parent().remove();
                    }
                });
            });
        };
    </script>
    @include('layouts.flash')
    @include('layouts.errors')
@endsection
