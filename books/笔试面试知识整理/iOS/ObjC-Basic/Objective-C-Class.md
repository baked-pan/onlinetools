## 类方法

OC中类的方法只有实例方法和静态方法两种：

```objective-c
@interface Controller : NSObject { NSString *something; }

+ (void)thisIsAStaticMethod; // 静态方法

– (void)thisIsAnInstanceMethod; // 实例方法

@end
```

OC中的方法只要声明在@interface里，就可以认为都是公有的。实际上，OC没有像Java，C++中的那种绝对的私有及保护成员方法，仅仅可以对调用者隐藏某些方法。

声明和实现都写在@implementation里的方法，类的外部是看不到的。

可以使用 Category 来实现私有方法：

```objective-c
// AClass.h
@interface AClass : NSObject

-(void)sayHello;

@end

// AClass.m
@interface AClass(private)

-(void)privateSayHello;

@end

@implementation AClass

-(void)sayHello {
    [self privateSayHello];
}

-(void)privateSayHello {
    NSLog(@"Private Hello");
}
```

使用这种方法时，试图调用`privateSayHello`会引起编译错误。

也可以使用 Extension 来实现私有方法：

```objective-c
// AClass.h 与上面相同

// AClass.m 
@interface AClass()

-(void)privateSayHello;

@end

@implementation AClass

-(void)sayHello {
    [self privateSayHello];
}

-(void)privateSayHello {
    NSLog(@"Private Hello");
}

@end
```

与使用 Category 类似，由于声明隐藏在 .m 中，调用者无法看到其声明，也就无法调用`privateSayHello`这个方法，在ARC下会引发编译错误。

和使用 Category 相比，使用 Extension 有以下两个好处：

1. Extension 声明的方法必须在类的主 @implementation 区间内实现，可以避免使用有名 Category 带来的多个不必要的 implementation 段。
2. 如果 Extension 中声明的方法没有实现，编译器会给出 Warning，使用 Category 则不会。

## 类变量

苹果推荐在OC中使用 @property 来实现成员变量：

```objective-c
@interface AClass : NSObject

@property (nonatomic, copy) NSString *name;

@end
```

使用@property声明的变量可以使用`实例名.变量名`来获取和修改。

@property可以看做是一种语法糖，在 MRC 下，使用 @property 可以看成实现了下面的代码：

```objective-c
// AClass.h
@interface AClass : NSObject{
@public
    NSString *_name;
}

-(NSString*) name;
-(void) setName:(NSString*)newName;
@end

// AClass.m
@implementation AClass

-(NSString*) name{
    return _name;
}

-(void) setName:(NSString *)name{
    if (_name != name) {
        [_name release];
        _name = [name copy];
    }
}
@end
```

也就是说，@property 会自动生成 getter 和 setter， 同时进行自动内存管理。

@property 的说明可以有以下几种：

* readwrite 是可读可写特性；需要生成getter方法和setter方法时
* readonly 是只读特性，只会生成getter方法 不会生成setter方法，不希望属性在类外改变时使用
* assign 是赋值特性，setter方法将传入参数赋值给实例变量；仅设置变量时；
* retain 表示持有特性，setter方法将传入参数先保留，再赋值，传入参数的retaincount会+1;
* copy 表示拷贝特性，setter方法将传入对象复制一份；需要完全一份新的变量时。
* nonatomic 和 atomic ，决定编译器生成的setter getter是否是原子操作。 atomic 表示使用原子操作，可以在一定程度上保证线程安全，一般使用nonatomic，nonatomic编译出的代码更快

默认的 @property 是 readwrite，assign，atomic

## 类的扩展——Protocol, Category 和 Extension

### Protocol 

OC是单继承的，OC中的类可以实现多个 protocol 来实现类似 C++ 中多重继承的效果。

Protocol 类似 Java 中的 interface，定义了一个方法列表，这个方法列表中的方法可以使用@required @optional 标注，以表示该方法是否是客户类必须要实现的方法。 一个 protocol 可以继承其他的 protocol 。

```objective-c
@protocol TestProtocol<NSObject> // NSObject也是一个 Protocol，这里即继承 NSObject 里的方法
-(void)Print;               
@end

@interface B : NSObject<TestProtocol>
-(void)Print; // 默认方法是@required的，即必须实现
@end

```

Delegate（委托）是 Cocoa 中常见的一种设计模式，其实现依赖于 protocol 这个语言特性。

### Category

Category 是一种很灵活的扩展原有类的机制，使用 Category 不需要访问原有类的代码，也无需继承。Category提供了一种简单的方式，来实现类的相关方法的模块化，把不同的类方法分配到不同的类文件中。

Category 常见的使用方法如下：

```objective-c
// SomeClass.h
@interface SomeClass : NSObject{
}
-(void) print;
@end 

// SomeClass+Hello.h
#import "SomeClass.h"
 
@interface SomeClass (Hello)
-(void)hello;
@end

// 实现
#import "SomeClass+Hello.h"
@implementationSomeClass (Hello)
-(void)hello{
    NSLog (@"name：%@ ", @"Jacky");
}
@end 
```

### Extension

Extension 可以认为是一种匿名的 Category， Extension 与 Category 有如下几点显著的区别：

1. 使用 Extension 必须有原有类的源码
2. Extension 可以在类中添加新的属性和实例变量，Category 不可以
3. Extension 里添加的方法必须要有实现

下面是一个 Extension 的例子：

```objective-c
@interface MyClass : NSObject  
- (float)value;  
@end  
              
@interface MyClass () { // 注意此处扩展的写法  
    float value;  
}  
- (void)setValue:(float)newValue;  
@end  
       
@implementation MyClass  
       
- (float)value {  
    return value;  
}  
       
- (void)setValue:(float)newValue {  
    value = newValue;  
}  
@end 
``` 

## 类的导入

导入类可以使用 `#include` , `#import` 和 `@class` 三种方法，其区别如下：

* `#import`是Objective-C导入头文件的关键字，`#include`是C/C++导入头文件的关键字
* 使用`#import`头文件会自动只导入一次，不会重复导入，相当于`#include`和`#pragma once`；
* `@class`告诉编译器需要知道某个类的声明，可以解决头文件的相互包含问题；

`@class`是放在interface中的，只是在引用一个类，将这个被引用类作为一个类型使用。在实现文件中，如果需要引用到被引用类的实体变量或者方法时，还需要使用`#import`方式引入被引用类。


### 参考资料

* [iOS开发基础面试题系列](http://blog.csdn.net/xunyn/article/details/8302787)
* [10个Objective-C基础面试题，iOS面试必备](http://www.oschina.net/news/42288/10-objective-c-interview)
* [Objective-C中“私有方法”的实现"](http://blog.sina.com.cn/s/blog_74e9d98d01013au8.html)
* [Objective-C中@property详解](http://www.cnblogs.com/andyque/archive/2011/08/03/2125728.html)
* [Objective-C中的protocol和delegate](http://www.cnblogs.com/whyandinside/archive/2013/02/28/2937217.html)
* [Objective-C——消息，Category 与 Protocol](http://www.cnblogs.com/chijianqiang/archive/2012/06/22/objc-category-protocol.html)
* [深入理解Objective-C中的@class](http://www.cnblogs.com/martin1009/archive/2012/06/24/2560218.html)