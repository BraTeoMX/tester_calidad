<?php
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

if (!function_exists('obtenerSegundasTerceras')) {
    function obtenerSegundasTerceras()
    {
        return Cache::remember('segundas_terceras', 60, function() {
            return DB::connection('sqlsrv')->select("
                 SELECT
                        ptt.OPRMODULEID_AT,
                        back.CATEGORYNAME,
                        ptt.PRODID,
                        pp.PRODTICKETID,
                        p.ITEMID,
                        inv.INVENTSIZEID,
                        inv.INVENTCOLORID,
                        ptt.QTY,
                        ptt.QUALITY,
                        CASE
                            WHEN ptt.QUALITY = 1 THEN 'Segunda'
                            WHEN ptt.QUALITY = 2 THEN 'Tercera'
                            ELSE 'N/A'
                        END AS Calidad,
                        CASE
                            WHEN ptt.QUALITY = 2 THEN 'N/A'
                            ELSE ptt.QUALITYCODEID
                        END AS QUALITYCODEID,
                        ISNULL(pqt.DESCRIPTION, 'N/A') AS DescripcionCalidad,
                        CASE
                            WHEN pqt.QUALITYCODEID BETWEEN 'A' AND 'G' THEN 'Segunda por Material'
                            WHEN pqt.QUALITYCODEID BETWEEN 'H' AND 'O' THEN 'Segunda por Costura'
                            ELSE 'N/A'
                        END AS [TipoSegunda],
                        inv.CONFIGID,
                        p.PRODPOOLID,
                        back.CUSTOMERNAME,
                        back.DIVISIONNAME,
                        back.ITEMNAME,
                        CONVERT(VARCHAR(10), ptt.TRANSDATE, 120) AS TRANSDATE
                    FROM [PRODTICKETTRANSTABLE_AT] ptt
                    INNER JOIN [PRODTABLE] p ON ptt.PRODID = p.PRODID
                    INNER JOIN [PRODTICKETSTABLE_AT] pp ON ptt.PRODTICKETID_AT = pp.PRODTICKETID
                    INNER JOIN [INVENTDIM] inv ON pp.INVENTDIMID = inv.INVENTDIMID
                    INNER JOIN [BACKLOGTABLE_AT] back ON p.INVENTREFID = back.SALESID AND inv.INVENTSIZEID = back.INVENTSIZEID AND p.ITEMID = back.ITEMID
                    LEFT JOIN [PACKINGQUALITYCODETABLE_AT] pqt ON ptt.QUALITYCODEID = pqt.QUALITYCODEID
                   WHERE ptt.TRANSDATE >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)
                     AND ptt.TRANSDATE < DATEADD(MONTH, 1, DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1))
                      AND ptt.QUALITY IN (1, 2)
            ");
        });
    }
}
