@extends('layouts.app')

@section('content')
    <h1>Publish a Post</h1>
    <hr />
    <form method="post" action="/posts">
        @csrf
        <div class="form-group">
            <label for="target">Target:</label>
            <input type="text" class="form-control" id="target" name="target">
        </div>
        <div class="form-group">
            <label for="question">Question:</label>
            <input type="text" class="form-control" id="quastion" name="question">
        </div>

        <input type="hidden" class="form-control" id="topic_id" name="topic_id" value="1">

        <div class="form-group">
            <label for="time">Time:</label>
            <input type="number" class="form-control" id="time" name="time" placeholder="60" min="30">
        </div>

        <div class="form-group">
            <label for="title">Options:</label>
            <div class="input-group control-group">
              <input type="text" name="addmore[]" class="form-control" placeholder="Option">
              <div class="input-group-btn"> 
                <button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
              </div>
            </div>
        </div>
        <!-- Copy Fields -->
        <div class="copy hide">
            <div class="form-group">
                <div class="control-group input-group" style="margin-top:10px">
                    <input type="text" name="addmore[]" class="form-control" placeholder="Option">
                    <div class="input-group-btn"> 
                      <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="after-add-more"></div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Publish</button>
        </div>
    </form>

    <script type="text/javascript">
        window.onload = function() {
            $(document).ready(function() {
                $(".add-more").click(function(){
                    var html = $(".copy").html();
                    $(".after-add-more").after(html);
                });

                $("body").on("click",".remove",function(){
                    if($(".control-group").length > 2) {
                        $(this).parents(".control-group").parent().remove();
                    }
                });
            });
        };
    </script>

    @include('layouts.errors')
@endsection
