<?php

require_once '../../config/auth.php';
require_once '../../config/db.php';

$id = intval($_GET['id'] ?? 0);

if(!$id){
    header("Location: inventory.php");
    exit;
}

/* Get Spare Part Details */

$sql = "
SELECT
    sp.*,
    m.manufacturer_name,
    c.category_name,
    v.vendor_name
FROM spare_parts sp
LEFT JOIN manufacturers m
    ON sp.manufacturer_id = m.id
LEFT JOIN equipment_categories c
    ON sp.category_id = c.id
LEFT JOIN vendors v
    ON sp.vendor_id = v.id
WHERE sp.id='$id'
";

$result = mysqli_query($conn,$sql);
$part = mysqli_fetch_assoc($result);

if(!$part){
    die("Spare Part Not Found");
}

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

?>

<div class="content-wrapper">

    <!-- Page Header -->
    <section class="content-header">
        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-6">
                    <h1>Spare Part Details</h1>
                </div>

                <div class="col-sm-6 text-right">

                    <a href="edit-part.php?id=<?= $id ?>"
                       class="btn btn-primary">

                        <i class="fas fa-edit"></i>
                        Edit

                    </a>

                    <a href="inventory.php"
                       class="btn btn-secondary">

                        <i class="fas fa-arrow-left"></i>
                        Back

                    </a>

                </div>

            </div>

        </div>
    </section>

    <!-- Content -->
    <section class="content">

        <div class="container-fluid">

            <!-- Barcode Card -->
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">
                        Barcode Information
                    </h3>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">

                            <h4>
                                <?=
                                !empty($part['barcode'])
                                ? htmlspecialchars($part['barcode'])
                                : 'No Barcode Assigned';
                                ?>
                            </h4>

                        </div>

                        <div class="col-md-6">

                            <p>
                                Type :
                                <strong>
                                    <?= htmlspecialchars($part['barcode_type'] ?? '-'); ?>
                                </strong>
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Spare Part Details -->
            <div class="card">

                <div class="card-header">

                    <h3 class="card-title">

                        <?= htmlspecialchars($part['part_name']); ?>

                    </h3>

                </div>

                <div class="card-body">

                    <table class="table table-bordered">

                        <tr>
                            <th width="25%">Part Code</th>
                            <td><?= htmlspecialchars($part['part_code']); ?></td>
                        </tr>

                        <tr>
                            <th>Part Name</th>
                            <td><?= htmlspecialchars($part['part_name']); ?></td>
                        </tr>

                        <tr>
                            <th>Barcode</th>
                            <td>
                                <?= !empty($part['barcode'])
                                    ? htmlspecialchars($part['barcode'])
                                    : '<span class="text-muted">Not Assigned</span>'; ?>
                            </td>
                        </tr>

                        <tr>
                            <th>QR Code</th>
                            <td>
                                <?= !empty($part['qr_code'])
                                    ? htmlspecialchars($part['qr_code'])
                                    : '<span class="text-muted">Not Assigned</span>'; ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Barcode Type</th>
                            <td>
                                <?= htmlspecialchars($part['barcode_type'] ?? '-'); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Manufacturer</th>
                            <td>
                                <?= htmlspecialchars($part['manufacturer_name'] ?? '-'); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Vendor</th>
                            <td>
                                <?= htmlspecialchars($part['vendor_name'] ?? '-'); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Equipment Category</th>
                            <td>
                                <?= htmlspecialchars($part['category_name'] ?? '-'); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Part Type</th>
                            <td>
                                <?= htmlspecialchars($part['part_type']); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Quantity</th>
                            <td>
                                <strong>
                                    <?= $part['quantity']; ?>
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <th>Reorder Level</th>
                            <td>
                                <?= $part['reorder_level']; ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Unit Price</th>
                            <td>
                                Rs.
                                <?= number_format($part['unit_price'],2); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Total Value</th>
                            <td>
                                Rs.
                                <?= number_format(
                                        $part['quantity']
                                        * $part['unit_price'],
                                        2
                                   ); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Store Location</th>
                            <td>
                                <?= htmlspecialchars($part['location'] ?? '-'); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Stock Status</th>
                            <td>

                                <?php

                                if($part['quantity']==0){

                                    echo '<span class="badge badge-danger">
                                            OUT OF STOCK
                                          </span>';

                                }
                                elseif($part['quantity'] <= $part['reorder_level']){

                                    echo '<span class="badge badge-warning">
                                            LOW STOCK
                                          </span>';

                                }
                                elseif($part['quantity'] <= ($part['reorder_level']*2)){

                                    echo '<span class="badge badge-info">
                                            LIMITED STOCK
                                          </span>';

                                }
                                else{

                                    echo '<span class="badge badge-success">
                                            AVAILABLE
                                          </span>';

                                }

                                ?>

                            </td>
                        </tr>

                    </table>

                </div>

            </div>

            <!-- Stock History -->
            <div class="card">

                <div class="card-header">

                    <h3 class="card-title">

                        Stock Usage History

                    </h3>

                </div>

                <div class="card-body">

                    <div class="alert alert-info mb-0">

                        <i class="fas fa-info-circle"></i>

                        This section will display all stock movements,
                        work orders and PM usage related to this spare part.

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>

<?php include '../../includes/footer.php'; ?>