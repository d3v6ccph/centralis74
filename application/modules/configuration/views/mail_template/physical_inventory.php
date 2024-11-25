<div style="width: 60%; margin: 0 auto;">
    <table style="width: 100%;">
        <tr>
            <td style="text-align: center; font-weight: bold; font-size: 18px; text-transform: uppercase;">
                <span>Physical Inventory</span>
                <?= !empty($core_data) ? " - " . $core_data->site_name : "" ?>
            </td>
        </tr>
    </table>
    <br>
    <table style="width: 80%; mso-table-lspace: 0pt!important; mso-table-rspace: 0pt!important;"
           align="center">
        <tr>
            <td width="130">REFERENCE NO:</td>
            <td><?= $data->main->ref_no ?></td>
            <td width="100">DATE:</td>
            <td width="200" style="text-transform: uppercase;">
                <?= date("M j,Y h:i A", strtotime($data->main->created_at)) ?>
            </td>
        </tr>
        <tr>
            <td>CREATED BY:</td>
            <td><?= strtoupper($data->main->created_by_name); ?></td>
            <td>VERIFIED BY:</td>
            <td><?= strtoupper($data->main->_confirmed_by) ?></td>
        </tr>
    </table>

    <br>

    <table width="100%" cellspacing="0" cellpadding="0"
           style="border: 1px solid #cbcbcb; border-collapse: collapse; mso-table-lspace: 0pt!important; mso-table-rspace: 0pt!important;">
        <thead>
        <tr>
            <th style="text-align: left; padding: 4px 8px; border: 1px solid #c6c6c6; border-bottom-width: 2px;">
                SKU
            </th>
            <th style="text-align: left; padding: 4px 8px; border: 1px solid #c6c6c6; border-bottom-width: 2px;">
                ITEM DESCRIPTION
            </th>
            <th style="text-align: center; padding: 4px 8px; border: 1px solid #c6c6c6; border-bottom-width: 2px;">
                CURRENT QTY.
            </th>
            <th style="text-align: center; padding: 4px 8px; border: 1px solid #c6c6c6; border-bottom-width: 2px;">
                PHYSICAL COUNT
            </th>
            <th style="text-align: center; padding: 4px 8px; border: 1px solid #c6c6c6; border-bottom-width: 2px;">
                VARIANCE
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data->contents as $content): ?>
            <tr>
                <td style="padding: 4px 8px; border: 1px solid #c6c6c6; width: 13%;">
                    <?= strtoupper($content->sku) ?>
                </td>
                <td style="padding: 4px 8px; border: 1px solid #c6c6c6;">
                    <?= strtoupper($content->name) ?>
                </td>
                <td style="padding: 4px 8px; border: 1px solid #c6c6c6; text-align: center; width: 15%;">
                    <?= $content->qty ?>
                </td>
                <td style="padding: 4px 8px; border: 1px solid #c6c6c6; text-align: center; width: 15%;">
                    <?= $content->count ?>
                </td>
                <td style="padding: 4px 8px; border: 1px solid #c6c6c6; text-align: center; width: 15%;">
                    <span style="<?= floatval($content->variance) < 0 ? 'font-weight: bold; color: #f4516c;' : 'font-weight: bold;' ?>">
                        <?= $content->variance ?>
                    </span>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <table width="100%" style="mso-table-lspace: 0pt!important; mso-table-rspace: 0pt!important;">
        <tr>
            <td width="80" style="font-weight: bold;">REMARKS:</td>
            <td><?= strtoupper($data->main->remarks) ?></td>
        </tr>
    </table>

    <!--<br><br><br>
    <table align="center" style="mso-table-lspace: 0pt!important; mso-table-rspace: 0pt!important;" width="80%">
        <?php /*if ((int)$data->main->status !== 0): */ ?>
            <tr>
                <td width="10%"></td>
                <td style="text-align: center; font-weight: bold; border-bottom: 1px solid #c6c6c6 !important; border-bottom: 6px;">
                    <? /*= strtoupper($data->main->created_by_name) */ ?>
                </td>
                <td width="30%"></td>
                <td style="text-align: center; font-weight: bold; border-bottom: 1px solid #c6c6c6 !important; border-bottom: 6px;">
                    <? /*= strtoupper($data->main->_confirmed_by) */ ?>
                </td>
                <td width="10%"></td>
            </tr>
            <tr>
                <td width="10%"></td>
                <td style="text-align: center; font-size: 12px;">CREATED BY</td>
                <td width="30%"></td>
                <td style="text-align: center; font-size: 12px;">
                    <? /*= (int)$data->main->status === 1 ? "VERIFIED BY" : "CANCELLED" */ ?>
                </td>
                <td width="10%"></td>
            </tr>
        <?php /*else: */ ?>
            <tr>
                <td width="35%"></td>
                <td style="text-align: center; font-weight: bold; border-bottom: 1px solid #c6c6c6 !important; border-bottom: 6px;">
                    <? /*= strtoupper($data->main->created_by_name) */ ?>
                </td>
                <td width="35%"></td>
            </tr>
            <tr>
                <td width="35%"></td>
                <td style="text-align: center; font-size: 12px;">CREATED BY</td>
                <td width="35%"></td>
            </tr>
        <?php /*endif; */ ?>
    </table>-->
</div>