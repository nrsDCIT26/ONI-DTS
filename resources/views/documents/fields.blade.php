<!-- Name Field -->
{!! Form::bsText('name') !!}
{{--if in edit mode--}}
@if ($document)
    @if (auth()->user()->can('update document '.$document->id) && !auth()->user()->is_super_admin)
        @foreach($document->tags->pluck('id')->toArray() as $tagId)
            <input type="hidden" name="tags[]" value="{{$tagId}}">
        @endforeach
    @else
    <div class="form-group col-sm-6">
    <label for="tags">{{ ucfirst(config('settings.tags_label_plural')) }}</label>
        <select class="form-control select2" id="tags" name="tags">
            <option value="">Select User:</option>
            @foreach($tags as $tag)
                @canany(['create documents', 'user manage permission', 'create documents in tag '.$tag->id])
                    <option value="{{$tag->id}}">{{$tag->name}}</option>
                @endcanany
            @endforeach
        </select>
    </div>
    @endif
@else
    <div class="form-group col-sm-6 {{ $errors->has('tags') ? 'has-error' :'' }}">
        <label for="tags">{{ucfirst(config('settings.tags_label_plural'))}}</label>
        <select class="form-control select2" id="tags" name="tags">
            <option value="">Select User:</option>
            @foreach($tags as $tag)
                @canany(['create documents', 'user manage permission', 'create documents in tag ' . $tag->id])
                    <option value="{{$tag->id}}">{{$tag->name}}</option>
                @endcanany
            @endforeach
        </select>
        {!! $errors->first('tags', '<span class="help-block">:message</span>') !!}
    </div>
    <input type="hidden" name="tags_string" id="tags_string">

    <script>
        document.querySelector('form').addEventListener('submit', function () {
            var selectedTags = Array.from(document.querySelectorAll('#tags option:checked')).map(option => option.value);
            document.querySelector('#tags_string').value = selectedTags.join(',');
        });
    </script>
@endif
{!! Form::bsTextarea('description',null,['class'=>'form-control b-wysihtml5-editor']) !!}


{{--additional Attributes--}}
@foreach ($customFields as $customField)
    <div class="form-group col-sm-6 {{ $errors->has("custom_fields.$customField->name") ? 'has-error' :'' }}">
        {!! Form::label("custom_fields[$customField->name]", Str::title(str_replace('_',' ',$customField->name)).":") !!}
        {!! Form::text("custom_fields[$customField->name]", null, ['class' => 'form-control typeahead','data-source'=>json_encode($customField->suggestions),'autocomplete'=>is_array($customField->suggestions)?'off':'on']) !!}
        {!! $errors->first("custom_fields.$customField->name",'<span class="help-block">:message</span>') !!}
    </div>
@endforeach
{{--end additional attributes--}}

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    {!! Form::submit('Save & Upload', ['class' => 'btn btn-primary','name'=>'savnup']) !!}
    <a href="{!! route('documents.index') !!}" class="btn btn-default">Cancel</a>
</div>