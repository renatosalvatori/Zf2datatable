<?php

namespace Zf2datatable\Examples\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use Zf2datatable\Entity\Entity;
/**
 * @ORM\Entity
 * @ORM\Table(name="person")
 * @Annotation\Name("Person")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 */
class Person extends Entity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"displayName:"})
     */
    protected $displayName;

    /**
     * @ORM\Column(type="string")
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"familyName:"})
     * @Annotation\Filter({"name":"StringTrim"})
     */
    protected $familyName;

    /**
     * @ORM\Column(type="string")
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"givenName:"})
     * @Annotation\Filter({"name":"StringTrim"})
     */
    protected $givenName;

    /**
     * @ORM\Column(type="string")
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"email:"})
     * @Annotation\Validator({"name":"StringLength"})
     * @Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Required({"true"})
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     * @Annotation\Type("Zend\Form\Element\Select")
     * @Annotation\Options({"label":"Description"})
     * @Annotation\Attributes({"options":{"f":"Famale","m":"Male"}})
     */
    protected $gender;

    /**
     * @ORM\Column(type="integer")
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"age:"})
     */
    protected $age;

    /**
     * @ORM\Column(type="decimal")
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"weight:"}) 
     */
    protected $weight;

    /**
     * @ORM\Column(type="datetime")
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"birthday:"})
     */
    protected $birthday;

    /**
     * @ORM\Column(type="datetime")
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"changeDate:"})
     */
    protected $changeDate;

    /**
     * @ORM\ManyToOne(targetEntity="Group")
     * @ORM\JoinColumn(name="primaryGroupId", referencedColumnName="id")
     * @Annotation\Exclude().
     */
    protected $primaryGroup;

    /**
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     *
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     *
     * @return string
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     *
     * @param string $familyName
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;
    }

    /**
     *
     * @return string
     */
    public function getGivenName()
    {
        return $this->givenName;
    }

    /**
     *
     * @param string $givenName
     */
    public function setGivenName($givenName)
    {
        $this->givenName = $givenName;
    }

    /**
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     *
     * @return string $gender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     *
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     *
     * @param integer $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     *
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     *
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     *
     * @param \DateTime $birthday
     */
    public function setBirthday($birthday)
    {
        if (is_string($birthday)) {
            $birthday = new \DateTime($birthday);
        }
        $this->birthday = $birthday;
    }

    /**
     *
     * @return \DateTime
     */
    public function getChangeDate()
    {
        return $this->changeDate;
    }

    /**
     *
     * @param \DateTime $changeDate
     */
    public function setChangeDate($changeDate)
    {
        if (is_string($changeDate)) {
            $changeDate = new \DateTime($changeDate);
        }
        $this->changeDate = $changeDate;
    }
}
