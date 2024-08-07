<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Calculation</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .highlight {
            background-color: #f0f0f0;
        }
        .over-5000 {
            color: red;
        }
        table {
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">การคำนวณสินเชื่อ</h1>
        <form id="loanForm" class="mb-5">
            <div class="form-group">
                <label for="principal">ยอดจัด:</label>
                <input type="text" class="form-control" id="principal" name="principal" placeholder="กรอกยอดจัด">
            </div>
            <div class="form-group">
                <label for="interest">ดอกเบี้ย (%):</label>
                <input type="text" class="form-control" id="interest" name="interest" placeholder="กรอกดอกเบี้ย">
            </div>
            <div class="form-group">
                <label for="terms">จำนวนงวด:</label>
                <input type="text" class="form-control" id="terms" name="terms" placeholder="กรอกจำนวนงวด">
            </div>
            <button type="button" id="calculate" class="btn btn-primary">คำนวณ</button>
            <button type="button" id="clear" class="btn btn-secondary" style="display:none;">เคลียร์ค่า</button>
        </form>
        <h2 class="text-center">ตารางผ่อนชำระ</h2>
        <table id="paymentTable" class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>งวด</th>
                    <th>ยอดผ่อน</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            function validateInput(value) {
                return /^\d+$/.test(value);
            }

            function calculatePayment(principal, interest, terms) {
                let monthlyRate = interest / 100 / 12;
                let payment = (principal * monthlyRate) / (1 - Math.pow(1 + monthlyRate, -terms));
                return payment.toFixed(2);
            }

            $('#principal').on('input', function() {
                let value = $(this).val();
                if (!validateInput(value) || value <= 0) {
                    alert("กรุณากรอกยอดจัดเป็นตัวเลขเท่านั้นและต้องมากกว่า 0");
                    $(this).val('');
                }
            });

            $('#calculate').on('click', function() {
                let principal = $('#principal').val();
                let interest = $('#interest').val();
                let terms = $('#terms').val();

                if (principal && interest && terms) {
                    let $tbody = $('#paymentTable tbody');
                    $tbody.empty();

                    for (let term = 12; term <= 84; term++) {
                        let payment = calculatePayment(principal, interest, term);
                        let $row = $('<tr>').data('term', term);

                        if (payment > 5000) {
                            $row.append($('<td>').text(term));
                            $row.append($('<td>').addClass('over-5000').text(payment));
                        } else {
                            $row.append($('<td>').text(term));
                            $row.append($('<td>').text(payment));
                        }

                        $tbody.append($row);
                    }

                    $('#clear').show();
                } else {
                    alert("กรุณากรอกข้อมูลให้ครบถ้วน");
                }
            });

            $('#paymentTable').on('click', 'tr', function() {
                $('#paymentTable tr').removeClass('highlight');
                $(this).addClass('highlight');
            });

            $('#clear').on('click', function() {
                $('#loanForm')[0].reset();
                $('#paymentTable tbody').empty();
                $(this).hide();
            });
        });
    </script>
</body>
</html>
