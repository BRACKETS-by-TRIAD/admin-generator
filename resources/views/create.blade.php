@extends('admin.layout.form', ['action' => url('admin/article/store')])

@section('title')
    <h1>Create article</h1>
@endsection

@section('body')

    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">Title</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="title" name="title" placeholder="Title">
        </div>
    </div>

    <div class="form-group">
        <label for="slug" class="col-sm-2 control-label">Slug</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="slug" name="slug" placeholder="Slug">
        </div>
    </div>

    <div class="form-group">
        <label for="perex" class="col-sm-2 control-label">Perex</label>
        <div class="col-sm-10">
            <textarea class="form-control" rows="3" id="perex" name="perex"></textarea>
        </div>
    </div>

    <div class="form-group">
        <label for="published_at" class="col-sm-2 control-label">Published at</label>
        <div class="col-sm-10">
            <input type="date" class="form-control" id="published_at" name="published_at">
        </div>
    </div>

    <div class="form-group">
        <label for="time_at" class="col-sm-2 control-label">Time</label>
        <div class="col-sm-10">
            <input type="time" class="form-control" id="time_at" name="time_at">
        </div>
    </div>

    <div class="form-group">
        <label for="reads" class="col-sm-2 control-label">Reads</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" id="reads" name="reads" placeholder="10">
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="is_published" value="1"> Is published?
                </label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="float_number" class="col-sm-2 control-label">Float number</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="float_number" name="float_number" placeholder="8.15">
        </div>

        <label for="decimal_number" class="col-sm-2 control-label">Decimal number</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="decimal_number" name="decimal_number" placeholder="87883.01">
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
@endsection