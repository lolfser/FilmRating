<section class="my-5">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Password ändern</h2>
            @if ($errors !== [])
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/user/change-password" method="post" style="border: 1px solid #CCC; padding: 5px;">
                @csrf
                <div class="form-group">
                    <label>aktuelles Passwort<br>
                        <input type="password" class="form-control" name="current_password" />
                    </label>
                </div>
                <div class="form-group">
                    <label>Neues Passwort<br>
                        <input type="password" class="form-control" name="password" />
                    </label>
                </div>
                <div class="form-group">
                    <label>Passwort wiederholen<br>
                        <input type="password" class="form-control" name="password_confirmation" />
                    </label>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Passwort aktualisieren</button>
                </div>
            </form>
        </div>
    </div>
</section>
