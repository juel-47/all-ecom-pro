<div class="tab-pane fade" id="list-email-config" role="tabpanel" aria-labelledby="list-email-config-list">
    <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
        <form action="{{route('admin.email-configuration-setting.update')}}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
            <div class="form-group col-md-12">
                <label>Email</label>
                <input type="text" class="form-control" name="email" value="{{@$emailConfiguration->email}}">
            </div>
            <div class="form-group col-md-12">
                <label>Mail Host</label>
                <input type="text" class="form-control" name="mail_host" value="{{@$emailConfiguration->host}}">
            </div>
            <div class="form-group col-md-6">
                <label>Smtp UserName</label>
                <input type="text" class="form-control" name="smtp_username" value="{{@$emailConfiguration->username}}">
            </div>
            <div class="form-group col-md-6">
                <label>Smtp Password</label>
                <input type="text" class="form-control" name="smtp_password" value="{{@$emailConfiguration->password}}">
            </div>
            <div class="form-group col-md-6">
                <label>Smtp Port</label>
                <input type="text" class="form-control" name="smtp_port" value="{{@$emailConfiguration->port}}">
            </div>
              <div class="form-group col-md-6">
                <label for="inputState">Email Encryption</label>
                <select id="inputState" class="form-control select2" name="email_encryption">
                    <option {{@$emailConfiguration->encryption == 'tls' ? 'selected' : ''}} value="tls">TLS</option>
                    <option {{@$emailConfiguration->encryption == 'ssl' ? 'selected' : ''}} value="ssl">SSL</option>
                </select>
            </div>
        </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

</div>
