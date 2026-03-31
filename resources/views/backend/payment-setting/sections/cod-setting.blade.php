<div class="tab-pane fade {{$activeTab === 'cod' ? 'show active' : ''}} " id="list-cod" role="tabpanel" aria-labelledby="list-cod-list">
    <form action="{{route('admin.cod-setting.update', 1)}}" method="post">
        @csrf
        @method('PUT')
        <div class="row">
        <div class="form-group col-md-12">
            <label for="inputState">COD Status</label>
            <select id="inputState" class="form-control" name="status">
                <option value="">Select</option>
              <option {{$codSetting?->status===1 ? 'selected' : ''}} value="1">Enable</option>
              <option {{$codSetting?->status===0? 'selected' : ''}} value="0">Disable</option>
            </select>
          </div>
    </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
