<?php
class Premius_Simpleshipping_Model_Shipping_Carrier_Simplecarrier
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'simplecarrier';
    
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $freeBoxes = 0;
        if ($request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {

                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }

                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                            $freeBoxes += $item->getQty() * $child->getQty();
                        }
                    }
                } elseif ($item->getFreeShipping()) {
                    $freeBoxes += $item->getQty();
                }
            }
        }
        $this->setFreeBoxes($freeBoxes);

        $result = Mage::getModel('shipping/rate_result');

		$packageValue = $request->getPackageValueWithDiscount();
		$minimalValue = $this->getConfigData('free_shipping_subtotal');
		
		$methodlist = array('A','B','C','D','E','F','G','H','I','J'); 
		foreach ($methodlist as $mtl)
		{
			if ($this->getConfigFlag('active'.$mtl))
			{
				if (($packageValue >= $minimalValue) && ($minimalValue > 0) && !$this->getConfigFlag('forcepaid'.$mtl))
				{
					$shippingPrice = '0.00';
				}
				else
				{
					$shippingPrice = $this->getConfigData('price'.$mtl);
				}
				$shippingPrice = $this->getFinalPriceWithHandlingFee($shippingPrice);
				
				$method = Mage::getModel('shipping/rate_result_method');
				$method->setCarrier($this->_code);
				$method->setCarrierTitle($this->getConfigData('title'));
				$method->setMethod('simplecarrier'.$mtl);
				$method->setMethodTitle($this->getConfigData('name'.$mtl));
				$method->setPrice($shippingPrice);
				$method->setCost($shippingPrice);
				$result->append($method);
			}
		}
		
		return $result;
		
		
    }
    
    public function getAllowedMethods()
    {
        return array($this->_code => $this->getConfigData('nameA'));
    }   
}