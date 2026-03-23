<?php


/**
 * @param  array  $models
 * @param  string  $columnName
 * @param  int  $id
 *
 * @return bool
 */
function canDelete($models, $columnName, $id)
{
    foreach ($models as $model) {
        $result = $model::where($columnName, $id)->exists();
        if ($result) {
            return true;
        }
    }

    return false;
}

 function syncTerritoriesAndFeesWithProduct($product, $installedApp){
    $switcher = new \App\Services\TerritoryFeesSwitcher();
    $fees = $switcher->getFees($installedApp);
    foreach($fees as $fee){
        \App\Models\fees::updateOrCreate(
            ['sid' => $product->store_id, 'wid' => $fee['wilaya_id'], 'product_id' => $product->id],
            [
                'app_id' => $installedApp->app_id,
                'o_s_p'  => $fee['fees'],
                'o_d_p'  => $fee['fees_stopdesk'],
                'c_s_p'  => $fee['fees'],
                'c_d_p'  => $fee['fees_stopdesk'],
            ]
        );
    }
}