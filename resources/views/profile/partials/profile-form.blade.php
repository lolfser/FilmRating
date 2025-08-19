<section class="my-5">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Profil-Informationen</h2>
            <br>
            @if ($errors !== [])
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="/user/profile-information" style="border: 1px solid #CCC; padding: 5px;" method="post">
                @csrf
                <div class="form-group">
                    <label for="state.email">Name<br>
                        <input type="text" class="form-control" name="name" value="<?php echo $user->name; ?>" />
                    </label>
                </div>
                <div class="form-group">
                    <label for="state.email">E-Mail-Adresse<br>
                        <input type="email" class="form-control" name="email"  value="<?php echo $user->email; ?>" />
                    </label>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Name / E-Mail-Adresse aktualisieren</button>
                </div>
            </form>
        </div>
    </div>
</section>
