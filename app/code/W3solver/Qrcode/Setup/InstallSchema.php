<?php
/**
 * W3solver.com
 *
 * @category    W3solver
 * @package     W3solver_Qrcode
 * @Description setup file
 * @author      W3solver
 */
namespace W3solver\Qrcode\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface 
{
    /**
     * install tables
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context) {
        //Initialize the Setup
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('w3solver_qrcode')) {
            $table = $installer->getConnection()->newTable(
                            $installer->getTable('w3solver_qrcode')
                    )
                    //add QRCode id field
                    ->addColumn(
                            'qrcode_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                            'identity' => true,
                            'nullable' => false,
                            'primary' => true,
                            'unsigned' => true,
                            ], 'QR Code ID'
                    )
                    //add QRCode image path field
                    ->addColumn(
                            'image_path', 
                            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 
                            255, 
                            [],
                            'QR Code Image'
                    )
                    //add Product id field
                    ->addColumn(
                            'product_id', 
                            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 
                            1, 
                            [], 
                            'Product Id'
                    )
                    //add Created at field
                    ->addColumn(
                            'created_at', 
                            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, 
                            null, 
                            [], 
                            'Post Created At'
                    )
                    //add Updated at field
                    ->addColumn(
                            'updated_at', 
                            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, 
                            null, 
                            [], 
                            'Post Updated At'
                    )
                    //Set Comment for the Table
                    ->setComment('Qrcode Table');

            $installer->getConnection()->createTable($table);
        }
        //Close the setup
        $installer->endSetup();
    }
}

