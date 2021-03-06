<?php

namespace Dr\MarketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TradingPair
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Dr\MarketBundle\Entity\TradingPairRepository")
 */
class TradingPair
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="remoteName", type="string", length=50)
     */
    private $remoteName;
    
    /**
     * @var Market 
     * @ORM\ManyToOne(targetEntity="Dr\MarketBundle\Entity\Market", inversedBy="tradingPairs")
     * @ORM\JoinColumn(name="market_id", referencedColumnName="id")
     */
    private $market;
    
    /**
     * @var Asset
     * "Price listed in"
     * @ORM\ManyToOne(targetEntity="Dr\MarketBundle\Entity\Asset")
     * @ORM\JoinColumn(name="assetFrom_id", referencedColumnName="id")
     */
    private $assetFrom;
    
    /**
     * @var Asset
     * "Asset traded (what is for sale?)"
     * @ORM\ManyToOne(targetEntity="Dr\MarketBundle\Entity\Asset")
     * @ORM\JoinColumn(name="assetTo_id", referencedColumnName="id")
     */
    private $assetTo;
    
    /**
     * @var boolean
     * @ORM\Column(name="isActive", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @var OrderBook
     *
     * @ORM\OneToMany(targetEntity="Dr\MarketBundle\Entity\OrderBook", mappedBy="tradingPair")
     */
    private $orderBooks;


    /**
     * @var Trade
     *
     * @ORM\OneToMany(targetEntity="Dr\MarketBundle\Entity\Trade", mappedBy="tradingPair")
     */
    private $trades;

    /**
     * @var integer
     *
     * @ORM\Column(name="refreshInterval", type="integer", nullable=true)
     */
    private $refreshInterval;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastRefresh", type="datetime", nullable=true)
     */
    private $lastRefresh;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="obLastRefresh", type="datetime", nullable=true)
     */
    private $orderBookLastRefresh;

    // MANUAL METHODS

    /**
     * __ctor
     * @param \Dr\MarketBundle\Entity\Market $market
     * @param string $name
     * @param string $remoteName
     * @return \Dr\MarketBundle\Entity\TradingPair
     */
    public function __construct(Market $market = null,  $name = null, $remoteName = null) {
        $this->setMarket($market);
        $this->setName($name);
        $this->setRemoteName($remoteName);
        $this->isActive = false;
        $this->refreshInterval = 10;
        return $this;
    }

    /**
     * @return $this
     */
    public function setRefreshed(){
        $this->lastRefresh = new \DateTime('now');

        return $this;
    }

    /**
     * @return $this
     */
    public function setOrderBookRefreshed(){
        $this->orderBookLastRefresh = new \DateTime('now');

        return $this;
    }

    /**
     * @return bool|\DateInterval|null
     */
    public function getLastRefreshInterval(){
        if($this->lastRefresh instanceof \DateTime){
            return $this->lastRefresh->diff(new \DateTime('now'));
        }else{
            return null;
        }
    }

    /**
     * @return bool|\DateInterval|null
     */
    public function getOrderBookLastRefreshInterval(){
        if($this->orderBookLastRefresh instanceof \DateTime){
            return $this->orderBookLastRefresh->diff(new \DateTime('now'));
        }else{
            return null;
        }
    }


    /**
     * @return bool|null
     */
    public function isStale(){
        $lastRefreshInterval = $this->getLastRefreshInterval();
        if($lastRefreshInterval instanceof \DateInterval){

            if($lastRefreshInterval->s >= $this->getRefreshInterval()){
                return true;
            }else{
                return false;
            }

        }

        return null;
    }

    /**
     * @return bool|null
     */
    public function isOrderBookStale(){
        $lastRefreshInterval = $this->getOrderBookLastRefreshInterval();
        if($lastRefreshInterval instanceof \DateInterval){

            if($lastRefreshInterval->s >= $this->getRefreshInterval()){
                return true;
            }else{
                return false;
            }

        }

        return null;
    }
    
    /**
     * @return string
     */
    public function __toString() {
        return $this->getName();
    }

    /**
     * @return boolean
     */
    public function isActive(){
        return $this->isActive;
    }
    
    /**
     * @return \Dr\MarketBundle\Entity\TradingPair
     */
    public function enable(){
        $this->isActive = true;
        return $this;
    }
    
    /**
     * @return \Dr\MarketBundle\Entity\TradingPair
     */
    public function disable(){
        $this->isActive = false;
        return $this;
    }


    // AUTO METHODS

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return TradingPair
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set remoteName
     *
     * @param string $remoteName
     * @return TradingPair
     */
    public function setRemoteName($remoteName)
    {
        $this->remoteName = $remoteName;

        return $this;
    }

    /**
     * Get remoteName
     *
     * @return string 
     */
    public function getRemoteName()
    {
        return $this->remoteName;
    }

    /**
     * Set market
     *
     * @param \Dr\MarketBundle\Entity\Market $market
     * @return TradingPair
     */
    public function setMarket(\Dr\MarketBundle\Entity\Market $market = null)
    {
        $this->market = $market;

        return $this;
    }

    /**
     * Get market
     *
     * @return \Dr\MarketBundle\Entity\Market 
     */
    public function getMarket()
    {
        return $this->market;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return TradingPair
     */
    public function setActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Set assetFrom
     *
     * @param \Dr\MarketBundle\Entity\Asset $assetFrom
     * @return TradingPair
     */
    public function setAssetFrom(\Dr\MarketBundle\Entity\Asset $assetFrom = null)
    {
        $this->assetFrom = $assetFrom;

        return $this;
    }

    /**
     * Get assetFrom
     *
     * @return \Dr\MarketBundle\Entity\Asset 
     */
    public function getAssetFrom()
    {
        return $this->assetFrom;
    }

    /**
     * Set assetTo
     *
     * @param \Dr\MarketBundle\Entity\Asset $assetTo
     * @return TradingPair
     */
    public function setAssetTo(\Dr\MarketBundle\Entity\Asset $assetTo = null)
    {
        $this->assetTo = $assetTo;

        return $this;
    }

    /**
     * Get assetTo
     *
     * @return \Dr\MarketBundle\Entity\Asset 
     */
    public function getAssetTo()
    {
        return $this->assetTo;
    }


    /**
     * Add orderBook
     *
     * @param \Dr\MarketBundle\Entity\OrderBook $orderBook
     *
     * @return TradingPair
     */
    public function addOrderBook(\Dr\MarketBundle\Entity\OrderBook $orderBook)
    {
        $this->orderBooks[] = $orderBook;

        return $this;
    }

    /**
     * Remove orderBook
     *
     * @param \Dr\MarketBundle\Entity\OrderBook $orderBook
     */
    public function removeOrderBook(\Dr\MarketBundle\Entity\OrderBook $orderBook)
    {
        $this->orderBooks->removeElement($orderBook);
    }

    /**
     * Get orderBooks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderBooks()
    {
        return $this->orderBooks;
    }

    /**
     * Set refreshInterval
     *
     * @param integer $refreshInterval
     *
     * @return TradingPair
     */
    public function setRefreshInterval($refreshInterval){
        if(!is_integer($refreshInterval) || $refreshInterval < 0){
            throw new \Exception('illegal parameter');
        }

        $this->refreshInterval = $refreshInterval;

        return $this;
    }

    /**
     * Get refreshInterval
     *
     * @return integer
     */
    public function getRefreshInterval()
    {
        return $this->refreshInterval;
    }

    /**
     * Set lastRefresh
     *
     * @param \DateTime $lastRefresh
     *
     * @return TradingPair
     */
    public function setLastRefresh($lastRefresh)
    {
        $this->lastRefresh = $lastRefresh;

        return $this;
    }

    /**
     * Get lastRefresh
     *
     * @return \DateTime
     */
    public function getLastRefresh()
    {
        return $this->lastRefresh;
    }

    /**
     * Set orderBookLastRefresh
     *
     * @param \DateTime $orderBookLastRefresh
     *
     * @return TradingPair
     */
    public function setOrderBookLastRefresh($orderBookLastRefresh)
    {
        $this->orderBookLastRefresh = $orderBookLastRefresh;

        return $this;
    }

    /**
     * Get orderBookLastRefresh
     *
     * @return \DateTime
     */
    public function getOrderBookLastRefresh()
    {
        return $this->orderBookLastRefresh;
    }

    /**
     * Get Trades
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrades(){
        return $this->trades;
    }


}
