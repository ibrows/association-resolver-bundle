# Important:

This Bundle is now stored here: https://gitlab.pwc-digital.ch/ec/bundles/IbrowsAssociationResolverBundle

<h1>iBrows Association Bundle</h1>
A simple bundle to resolve associations on entities when they'll be imported from an external source

<h2></h2>

<h3>Create an annotation</h3>
<i>See Annotation/OneToMany.php</i>

In this case I'm going to create a new OneToMany-annotation for this bundle. Therefore I create a new class called OneToMany in the 'Annotation' folder
and annotate the class with <code>@Annotation</code> and extend it from the <code>AbstractAssociation</code>
If you need to add some properties which can be configured in the annotation create a public property in the Annotation and create the getter/setter

<h3>Create the resolver</h3>
<i>See Resolver/Type/OneToMany.php</i>

To handle the entity with our annotation @OneToMany we have to create a resolver with the exact same name as the annotation class name.
Our resolver extends the AbstractResolver to handle the annotated entity correctly. While extending the AbstractResolver we have to implement an abstract function to handle an entity and recive all the configurated properties. 
<pre>
    <code>
        /**
         * @param ResultBag $resultBag
         * @param AssociationMappingInfoInterface $mappingInfo
         * @param string $propertyName
         * @param mixed $entity
         * @param OutputInterface $output
         * @return ResolverInterface
         */
        public function resolveAssociation(
            ResultBag $resultBag,
            AssociationMappingInfoInterface $mappingInfo,
            $propertyName,
            $entity,
            OutputInterface $output
        )
    </code>
</pre>

in the function body we extract the data from the mappinginfo for further operations. Inside the MappingInfo object where two properties. The Annotation and the Metadata. The Annotation object holds all the data which can be configured in the annotation. In our case we have 2 properties
<pre>
public $collectionAddFunctionName;
public $collectionRemoveFunctionName;
</pre>

<code>collectionAddFunctionName</code> to customize the add function. If this property is not set the method name will be generated with an 'add' as prefix and the propertyname as suffix

<code>collectionRemoveFunctionName</code> to customize the remove function. If this property is not set the method name will be generated with an 'remove' as prefix and the propertyname as suffix

<h3>The Service</h3>
To make the resolver functional you have to register the resolver as a service in the service.xml

    
    <service id="ibrows_associationresolver.resolver.onetoone" class="Ibrows\AssociationResolver\Resolver\Type\OneToOne">
        <argument type="service" id="doctrine.orm.entity_manager" />
        <tag priority="-20" name="ibrows_associationresolver.resolverchain" />
        <call method="setSoftdeletable">
            <argument>%ibrows_associationresolver.softdelete%</argument>
        </call>
        <call method="setSoftdeletableGetter">
            <argument>%ibrows_associationresolver.softdeletegetter%</argument>
        </call>
    </service>
    ´
        
The tag defines the position in the chain.
