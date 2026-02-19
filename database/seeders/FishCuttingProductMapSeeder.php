<?php

namespace Database\Seeders;

use App\Models\FishCuttingProductMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FishCuttingProductMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $fish_cutting_product_maps = array(
            array(
                // "id" => 1,
                "main_product_id" => 1,
                "grouped_product" => "[{\"product_id\":\"2\",\"type\":\"1\",\"percentage\":\"81\"},{\"product_id\":\"5\",\"type\":\"2\",\"percentage\":\"12\"}]",
                "wastage_percentage" => 7.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "SEER HEAD\t12%\r\nSEER SLICE\t81%\r\nSEER ROE\t\r\nWASTE\t7%",
                "created_at" => "2024-04-26T07:43:53.570Z",
                "updated_at" => "2024-04-26T07:43:53.570Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 2,
                "main_product_id" => 7,
                "grouped_product" => "[{\"product_id\":\"126\",\"type\":\"1\",\"percentage\":\"70\"},{\"product_id\":\"191\",\"type\":\"2\",\"percentage\":\"20\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "B POM HEAD\t20%\r\nB POM SLICE\t70%\r\nBLACK POMFRET ROE\t\r\n WASTE\t10%",
                "created_at" => "2024-04-26T07:45:23.273Z",
                "updated_at" => "2024-04-26T09:11:47.237Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 3,
                "main_product_id" => 65,
                "grouped_product" => "[{\"product_id\":\"192\",\"type\":\"2\",\"percentage\":\"18\"},{\"product_id\":\"66\",\"type\":\"1\",\"percentage\":\"72\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "BARRACUDA YELLOWTAIL HEAD\t18%\r\nBARRACUDA YELLOWTAIL SLICE\t72%\r\nBARRACUDA YELLOWTAIL ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:15:19.480Z",
                "updated_at" => "2024-04-26T09:15:19.480Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 4,
                "main_product_id" => 113,
                "grouped_product" => "[{\"product_id\":\"193\",\"type\":\"2\",\"percentage\":\"18\"},{\"product_id\":\"127\",\"type\":\"1\",\"percentage\":\"72\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "BARRACUDA HEAD\t18%\r\nBARRACUDA BLACK SLICE\t72%\r\nBARRACUDA BLACK ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:16:41.573Z",
                "updated_at" => "2024-04-26T09:16:41.573Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 5,
                "main_product_id" => 11,
                "grouped_product" => "[{\"product_id\":\"194\",\"type\":\"2\",\"percentage\":\"20\"},{\"product_id\":\"12\",\"type\":\"1\",\"percentage\":\"70\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "BEKTI HEAD\t20%\r\nBEKTI SLICE\t70%\r\nBEKTI ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:17:26.960Z",
                "updated_at" => "2024-04-26T09:17:26.960Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 6,
                "main_product_id" => 36,
                "grouped_product" => "[{\"product_id\":\"195\",\"type\":\"2\",\"percentage\":\"18\"},{\"product_id\":\"78\",\"type\":\"1\",\"percentage\":\"72\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "BELONE HEAD\t18%\r\nBELONE SLICE\t72%\r\nBELONE ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:18:32.777Z",
                "updated_at" => "2024-04-26T09:18:32.777Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 7,
                "main_product_id" => 109,
                "grouped_product" => "[{\"product_id\":\"196\",\"type\":\"2\",\"percentage\":\"20\"},{\"product_id\":\"77\",\"type\":\"1\",\"percentage\":\"70\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "CARANX HEAD\t20%\r\nCARANX SLICE\t70%\r\nCARANX BIG ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:19:24.043Z",
                "updated_at" => "2024-04-26T09:19:24.043Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 8,
                "main_product_id" => 140,
                "grouped_product" => "[{\"product_id\":\"197\",\"type\":\"2\",\"percentage\":\"24\"},{\"product_id\":\"43\",\"type\":\"1\",\"percentage\":\"64\"}]",
                "wastage_percentage" => 12.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "CATLA HEAD\t24%\r\nCATLA SLICE\t64%\r\nCATLA BIG ROE\t\r\nWASTE\t12%",
                "created_at" => "2024-04-26T09:20:10.867Z",
                "updated_at" => "2024-04-26T09:20:10.867Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 9,
                "main_product_id" => 75,
                "grouped_product" => "[{\"product_id\":\"198\",\"type\":\"2\",\"percentage\":\"23\"},{\"product_id\":\"76\",\"type\":\"1\",\"percentage\":\"65\"}]",
                "wastage_percentage" => 12.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "GROUPER HEAD\t23%\r\nGROUPER SLICE\t65%\r\nGROUPER BIG ROE\t\r\nWASTE\t12%",
                "created_at" => "2024-04-26T09:21:00.097Z",
                "updated_at" => "2024-04-26T09:21:00.097Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 10,
                "main_product_id" => 146,
                "grouped_product" => "[{\"product_id\":\"199\",\"type\":\"2\",\"percentage\":\"20\"},{\"product_id\":\"147\",\"type\":\"1\",\"percentage\":\"67\"}]",
                "wastage_percentage" => 13.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "H SHARK HEAD\t20%\r\nH SHARK SLICE\t67%\r\nWASTE\t13%",
                "created_at" => "2024-04-26T09:21:53.823Z",
                "updated_at" => "2024-04-26T09:21:53.823Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 11,
                "main_product_id" => 92,
                "grouped_product" => "[{\"product_id\":\"200\",\"type\":\"2\",\"percentage\":\"12\"},{\"product_id\":\"148\",\"type\":\"1\",\"percentage\":\"81\"}]",
                "wastage_percentage" => 7.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "H KOLA HEAD\t12%\r\nH KOLA SLICE\t81%\r\nH KOLA ROE\t\r\nWASTE\t7%",
                "created_at" => "2024-04-26T09:23:00.237Z",
                "updated_at" => "2024-04-26T09:23:00.237Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 12,
                "main_product_id" => 39,
                "grouped_product" => "[{\"product_id\":\"201\",\"type\":\"2\",\"percentage\":\"20\"},{\"product_id\":\"64\",\"type\":\"1\",\"percentage\":\"70\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "KADAL VIRAL HEAD\t20%\r\nKADAL VIRAL SLICE\t70%\r\nKADAL VIRAL ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:23:41.137Z",
                "updated_at" => "2024-04-26T09:23:41.137Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 13,
                "main_product_id" => 90,
                "grouped_product" => "[{\"product_id\":\"202\",\"type\":\"2\",\"percentage\":\"18\"},{\"product_id\":\"30\",\"type\":\"1\",\"percentage\":\"72\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "KALA HEAD\t18%\r\nKALA SLICE\t72%\r\nKALA FULL BIG ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:24:33.317Z",
                "updated_at" => "2024-04-26T09:24:33.317Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 14,
                "main_product_id" => 72,
                "grouped_product" => "[{\"product_id\":\"203\",\"type\":\"2\",\"percentage\":\"20\"},{\"product_id\":\"73\",\"type\":\"1\",\"percentage\":\"70\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "KANNADI PARAI HEAD\t20%\r\nKANNADI PARAI SLICE\t70%\r\nKANNADI PARAI ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:26:16.500Z",
                "updated_at" => "2024-04-26T09:26:16.500Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 15,
                "main_product_id" => 122,
                "grouped_product" => "[{\"product_id\":\"204\",\"type\":\"2\",\"percentage\":\"18\"},{\"product_id\":\"123\",\"type\":\"1\",\"percentage\":\"72\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "KATTA HEAD\t18%\r\nKATTA SLICE\t72%\r\nKATTA ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:27:52.597Z",
                "updated_at" => "2024-04-26T09:27:52.597Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 16,
                "main_product_id" => 83,
                "grouped_product" => "[{\"product_id\":\"205\",\"type\":\"2\",\"percentage\":\"20\"},{\"product_id\":\"150\",\"type\":\"1\",\"percentage\":\"70\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "KORALI HEAD\t20%\r\nKORALI SLICE\t70%\r\nKORALI BIG ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:28:46.523Z",
                "updated_at" => "2024-04-26T09:28:46.523Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 17,
                "main_product_id" => 152,
                "grouped_product" => "[{\"product_id\":\"206\",\"type\":\"2\",\"percentage\":\"20\"},{\"product_id\":\"153\",\"type\":\"1\",\"percentage\":\"70\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "KURAKATHALA HEAD\t20%\r\nKURAKATHALA SLICE\t70%\r\nKURAKATHALA FULL ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:29:36.830Z",
                "updated_at" => "2024-04-26T09:29:36.830Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 18,
                "main_product_id" => 13,
                "grouped_product" => "[{\"product_id\":\"207\",\"type\":\"2\",\"percentage\":\"20\"},{\"product_id\":\"14\",\"type\":\"1\",\"percentage\":\"70\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "LETHRINUS HEAD\t20%\r\nLETHRINUS SLICE\t70%\r\nLETHRINUS FULL ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:31:08.103Z",
                "updated_at" => "2024-04-26T09:31:08.103Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 19,
                "main_product_id" => 46,
                "grouped_product" => "[{\"product_id\":\"208\",\"type\":\"2\",\"percentage\":\"20\"},{\"product_id\":\"47\",\"type\":\"1\",\"percentage\":\"68\"}]",
                "wastage_percentage" => 12.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "MRIGAL HEAD\t20%\r\nMRIGAL SLICE\t68%\r\nMRIGAL FULL ROE\t\r\nWASTE\t12%",
                "created_at" => "2024-04-26T09:32:10.720Z",
                "updated_at" => "2024-04-26T09:32:10.720Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 20,
                "main_product_id" => 28,
                "grouped_product" => "[{\"product_id\":\"209\",\"type\":\"2\",\"percentage\":\"20\"},{\"product_id\":\"155\",\"type\":\"1\",\"percentage\":\"68\"}]",
                "wastage_percentage" => 12.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "MULLET HEAD\t20%\r\nMULLET SLICE\t68%\r\nMULLET BIG ROE\t\r\nWASTE\t12%",
                "created_at" => "2024-04-26T09:32:56.533Z",
                "updated_at" => "2024-04-26T09:32:56.533Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 21,
                "main_product_id" => 79,
                "grouped_product" => "[{\"product_id\":\"210\",\"type\":\"2\",\"percentage\":\"20\"},{\"product_id\":\"80\",\"type\":\"1\",\"percentage\":\"70\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "PARALA HEAD\t20%\r\nPARALA SLICE\t70%\r\nPARALA ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:33:46.137Z",
                "updated_at" => "2024-04-26T09:33:46.137Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 22,
                "main_product_id" => 159,
                "grouped_product" => "[{\"product_id\":\"211\",\"type\":\"2\",\"percentage\":\"20\"},{\"product_id\":\"69\",\"type\":\"1\",\"percentage\":\"70\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "PARROT HEAD\t20%\r\nPARROT SLICE\t70%\r\nPARROT BIG ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:34:32.953Z",
                "updated_at" => "2024-04-26T09:34:32.953Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 23,
                "main_product_id" => 160,
                "grouped_product" => "[{\"product_id\":\"212\",\"type\":\"2\",\"percentage\":\"22\"},{\"product_id\":\"16\",\"type\":\"1\",\"percentage\":\"68\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "PERCH HEAD\t22%\r\nPERCH SLICE\t68%\r\nPERCH BIG ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:35:44.203Z",
                "updated_at" => "2024-04-26T09:35:44.203Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 24,
                "main_product_id" => 162,
                "grouped_product" => "[{\"product_id\":\"213\",\"type\":\"2\",\"percentage\":\"22\"},{\"product_id\":\"163\",\"type\":\"1\",\"percentage\":\"68\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "PLAIN GROUPER HEAD\t22%\r\nPLAIN GROUPER SLICE\t68%\r\nPLAIN GROUPER ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:37:34.273Z",
                "updated_at" => "2024-04-26T09:37:34.273Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 25,
                "main_product_id" => 59,
                "grouped_product" => "[{\"product_id\":\"214\",\"type\":\"2\",\"percentage\":\"15\"},{\"product_id\":\"85\",\"type\":\"1\",\"percentage\":\"60\"}]",
                "wastage_percentage" => 25.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "RAY FISH HEAD\t15%\r\nRAY FISH SLICE\t60%\r\nRAY FISH ROE\t\r\nWASTE\t25%",
                "created_at" => "2024-04-26T09:38:15.613Z",
                "updated_at" => "2024-04-26T09:38:15.613Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 26,
                "main_product_id" => 70,
                "grouped_product" => "[{\"product_id\":\"215\",\"type\":\"2\",\"percentage\":\"22\"},{\"product_id\":\"71\",\"type\":\"1\",\"percentage\":\"68\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "RED SNAPPER HEAD\t22%\r\nRED SNAPPER SLICE\t68%\r\nRED SNAPPER BIG ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:39:51.360Z",
                "updated_at" => "2024-04-26T09:39:51.360Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 27,
                "main_product_id" => 168,
                "grouped_product" => "[{\"product_id\":\"216\",\"type\":\"2\",\"percentage\":\"20\"},{\"product_id\":\"120\",\"type\":\"1\",\"percentage\":\"70\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "RIVER POMFRET HEAD\t20%\r\nRIVER POMFRET SLICE\t70%\r\nRIVER POMFRET BIG ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:40:35.820Z",
                "updated_at" => "2024-04-26T09:40:35.820Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 28,
                "main_product_id" => 170,
                "grouped_product" => "[{\"product_id\":\"217\",\"type\":\"2\",\"percentage\":\"23\"},{\"product_id\":\"45\",\"type\":\"1\",\"percentage\":\"65\"}]",
                "wastage_percentage" => 12.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "ROHU HEAD\t23%\r\nROHU SLICE\t65%\r\nROHU BIG ROE\t\r\nWASTE\t12%",
                "created_at" => "2024-04-26T09:41:23.597Z",
                "updated_at" => "2024-04-26T09:41:23.597Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 29,
                "main_product_id" => 117,
                "grouped_product" => "[{\"product_id\":\"218\",\"type\":\"2\",\"percentage\":\"22\"},{\"product_id\":\"118\",\"type\":\"1\",\"percentage\":\"68\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "SADAIYAN HEAD\t22%\r\nSADAIYAN SLICE\t68%\r\nSADAIYAN FULL ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:41:59.113Z",
                "updated_at" => "2024-04-26T09:42:25.600Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 30,
                "main_product_id" => 3,
                "grouped_product" => "[{\"product_id\":\"219\",\"type\":\"2\",\"percentage\":\"12\"},{\"product_id\":\"173\",\"type\":\"1\",\"percentage\":\"81\"}]",
                "wastage_percentage" => 7.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "SEER M HEAD\t12%\r\nSEER M SLICE\t81%\r\nSEER M 2 ROE\t\r\nWASTE\t7%",
                "created_at" => "2024-04-26T09:43:08.413Z",
                "updated_at" => "2024-04-26T09:43:08.413Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 31,
                "main_product_id" => 9,
                "grouped_product" => "[{\"product_id\":\"220\",\"type\":\"2\",\"percentage\":\"18\"},{\"product_id\":\"10\",\"type\":\"1\",\"percentage\":\"63\"}]",
                "wastage_percentage" => 19.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "SHARK HEAD\t18%\r\nSHARK SLICE\t63%\r\nWASTE\t19%",
                "created_at" => "2024-04-26T09:43:49.633Z",
                "updated_at" => "2024-04-26T09:43:49.633Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 32,
                "main_product_id" => 24,
                "grouped_product" => "[{\"product_id\":\"221\",\"type\":\"2\",\"percentage\":\"20\"},{\"product_id\":\"176\",\"type\":\"1\",\"percentage\":\"70\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "SOLE FISH HEAD\t20%\r\nSOLE FISH SLICE\t70%\r\nSOLE FISH ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:44:34.057Z",
                "updated_at" => "2024-04-26T09:44:34.057Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 33,
                "main_product_id" => 86,
                "grouped_product" => "[{\"product_id\":\"222\",\"type\":\"2\",\"percentage\":\"20\"},{\"product_id\":\"87\",\"type\":\"1\",\"percentage\":\"70\"}]",
                "wastage_percentage" => 10.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "THOL PARAI HEAD\t20%\r\nTHOL PARAI SLICE\t70%\r\nTHOL PARAI ROE\t\r\nWASTE\t10%",
                "created_at" => "2024-04-26T09:45:14.087Z",
                "updated_at" => "2024-04-26T09:45:14.087Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 34,
                "main_product_id" => 178,
                "grouped_product" => "[{\"product_id\":\"223\",\"type\":\"2\",\"percentage\":\"22\"},{\"product_id\":\"179\",\"type\":\"1\",\"percentage\":\"65\"}]",
                "wastage_percentage" => 13.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "TUNA HEAD\t22%\r\nTUNA SLICE\t65%\r\nTUNA BIG ROE\t\r\nWASTE\t13%",
                "created_at" => "2024-04-26T09:45:51.613Z",
                "updated_at" => "2024-04-26T09:45:51.613Z",
                "deleted_at" => null
            ),
            array(
                // "id" => 35,
                "main_product_id" => 180,
                "grouped_product" => "[{\"product_id\":\"224\",\"type\":\"2\",\"percentage\":\"23\"},{\"product_id\":\"74\",\"type\":\"1\",\"percentage\":\"63\"}]",
                "wastage_percentage" => 14.00,
                "status" => 1,
                "created_by" => 1,
                "updated_by" => 1,
                "remarks" => "TUNA YELLOW FIN HEAD\t23%\r\nTUNA YELLOW FIN SLICE\t63%\r\nTUNA YELLOW FIN ROE\t\r\nWASTE\t14%",
                "created_at" => "2024-04-26T09:46:32.840Z",
                "updated_at" => "2024-04-26T09:46:32.840Z",
                "deleted_at" => null
            )
        );
        DB::beginTransaction();


        foreach ($fish_cutting_product_maps as $fish_cutting_product_map) {
            // DB::unprepared('SET IDENTITY_INSERT transport_types ON');
            FishCuttingProductMap::create($fish_cutting_product_map);
            // DB::unprepared('SET IDENTITY_INSERT states OFF');
        }


        DB::commit();
    }
}
