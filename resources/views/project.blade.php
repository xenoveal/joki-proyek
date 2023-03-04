<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contoh Tabel dengan Bootstrap</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">

        <h1><br>List Project</h1>
        <br>
        <div id="alertContainer" class="mt-3"></div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Project Details</th>
                    <th>Project Type</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Deal Price</th>
                    <th>Payment Proof</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $k => $p)
                    <tr>
                        <td>{{$k + 1}}</td>
                        <td>{{$p -> user -> email}}</td>
                        <td>{{$p -> user -> phone_number}}</td>
                        <td>{{ $p -> project_details }}</td>
                        <td>{{ $p -> project_type }}</td>
                        <td>{{ $p -> deadline }}</td>
                        <td>
                            <form id="form-data">
                                <input type="hidden" value="{{ $p -> project_id }}" name="project_id" id="project_id">
                                <select class="form-control" name="status" id="status">
                                    <option value="on-request">On Request</option>
                                    <option value="on-first-reply">On First Replay</option>
                                    <option value="on-discussion">On Discussion</option>
                                    <option value="need-payment">Need Payment</option>
                                    <option value="payment-on-confirmation">Payment On Confirmation</option>
                                    <option value="on-development">On Development</option>
                                    <option value="finalization">Finalization</option>
                                    <option value="done-success">Done Success</option>
                                    <option value="done-fail">Done Fail</option>
                                </select>
                            </form>
                        </td>
                        <td>{{ $p -> deal }}</td>
                        <td>
                            <a href="http://127.0.0.1:8000/storage/payment_proof/{{$p -> payment_proof}}" target="_blank">
                                <button type="button" class="btn btn-outline-primary">
                                    <i class="bi bi-eye"></i> Show
                                </button>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#status').change(function() {
                var project_id = $('#project_id').val();
                var status = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '/change-status',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        project_id: project_id,
                        status: status },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response.status);
                        showAlert('success', 'Status project berhasil diubah!');
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan saat menyimpan data ke database.');
                    }
                });
            });

            function showAlert(type, message) {
				var alertClass = '';
				switch(type) {
					case 'success':
						alertClass = 'alert-success';
						break;
					case 'warning':
						alertClass = 'alert-warning';
						break;
					case 'danger':
						alertClass = 'alert-danger';
						break;
				}
				var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' + message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
				$('#alertContainer').html(alertHtml);
			}
        });
    </script>
</body>
</html>
